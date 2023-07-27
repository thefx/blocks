<?php

namespace thefx\blocks\models\forms;

use thefx\blocks\behaviors\SlugBehavior;
use thefx\blocks\behaviors\UploadImageBehavior;
use thefx\blocks\models\BlockItem;
use thefx\blocks\models\BlockItemPropertyAssignments;
use thefx\blocks\models\BlockProperty;
use thefx\blocks\traits\TransactionTrait;
use Yii;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "{{%block_item}}".
 *
 * @property BlockProperty[] $propertiesAll
 */
class BlockItemForm extends BlockItem
{
    use TransactionTrait;

    /**
     * @var string
     */
    public $photo_preview_crop;

    /**
     * @var string
     */
    public $photo_crop;

    /**
     * @var BlockItemPropertyAssignments[]
     */
    public $propertyAssignmentsUpdate;

    /**
     * @var BlockItemPropertyAssignments[]
     */
    public $propertyAssignmentsInsert;

    public static function create($block_id, $section_id)
    {
        $model = new static();
        $model->block_id = $block_id;
        $model->section_id = $section_id;
        $model->sort = 100;
        $model->public = 1;
        $model->create_user = Yii::$app->user->id;
        $model->create_date = date('Y-m-d H:i:s');
        $model->populateAssignments();

        return $model;
    }

    public static function findForUpdate($id)
    {
        $model = static::find()
            ->with([
                'propertiesAll.elements',
                'propertiesAll.blockItemsList',
                'propertyAssignments.property.elements',
                'propertyAssignments.property.blockItemsList',
            ])
            ->where(['id' => $id])
            ->one();

        if ($model) {
            $model->update_user = Yii::$app->user->id;
            $model->update_date = date('Y-m-d H:i:s');
            $model->populateAssignments();
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

//    public function validate($attributeNames = null, $clearErrors = true)
//    {
//        return parent::validate($attributeNames, $clearErrors) && self::validateMultiple($this->propertyAssignmentsUpdate);
//    }

//    public function load($data, $formName = null)
//    {
//        return parent::load($data, $formName) && $this->loadPropertyAssignments($data);
//    }

    public function save($runValidation = true, $attributeNames = null)
    {
        if (! $this->validate()) {
            return false;
        }
        $this->wrap(function () use ($runValidation, $attributeNames) {
            // save content
            parent::save($runValidation, $attributeNames);

            // save properties
            BlockItemPropertyAssignments::deleteAll(['block_item_id' => $this->id]);

            $propertyAssignments = [];

            foreach ($this->propertyAssignmentsInsert as $assignment) {
                if ($assignment->value === '') {
                    continue;
                }
                $propertyAssignments[] = [
                    'block_item_id' => $this->id, // for new records
                    'property_id' => $assignment->property_id,
                    'value' => $assignment->value,
                ];
            }

            if (!empty($propertyAssignments)) {
                Yii::$app->db->createCommand()->batchInsert(
                    BlockItemPropertyAssignments::tableName(),
                    array_keys(reset($propertyAssignments)),
                    $propertyAssignments
                )->execute();
            }

            TagDependency::invalidate(Yii::$app->cache, 'block_items_' . $this->block_id);
        });
        return true;
    }

    protected function populateAssignments()
    {
        $assignments = [];

        foreach ($this->propertiesAll as $property) {
            $isset = false;
            foreach ($this->propertyAssignments as $assignment) {
                if ($assignment->isForProperty($property->id)) {
                    if ($property->isMultiple()) {

                        if ($property->isList() || $property->isRelativeItem() || $property->isFile()) {
                            if (isset($assignments[$property->id][0]) && is_array($assignments[$property->id][0]->value)) {
                                $values = $assignments[$property->id][0]->value;
                                $values[] = $assignment->value;
                                $assignments[$property->id][0]->value = $values;
                            } else {
                                $assignment->value = [$assignment->value];
                                $assignments[$property->id][0] = $assignment;
                            }
                        }
                        else /*if ($property->isText() || $property->isString())*/ {
                            $assignments[$property->id][0] = $assignment;
                        }
                    }
                    else {
                        $assignments[$property->id][$assignment->id] = $assignment;
                    }
                    $isset = true;
                }
            }
            if (!$isset) {
                $newAssignment = new BlockItemPropertyAssignments([
                    'property_id' => $property->id,
                    'block_item_id' => $this->id,
                ]);
                if ($property->isMultiple() && $property->isFile()) {
                    $newAssignment->value = [];
                }
                $newAssignment->populateRelation('property', $property);
                $assignments[$property->id]['new1'] = $newAssignment;
            }
        }

        $this->propertyAssignmentsUpdate = $assignments;

        return $this;
    }

    public function loadPropertyAssignments($data)
    {
        $assignmentsForInsert = [];
        $validate = true;

        foreach ($this->propertyAssignmentsUpdate as $propertyId => $assignments) {
            foreach ($assignments as $assignmentId => $assignment) {
                $assignment->setAttributes($data[$assignment->formName()][$propertyId][$assignmentId]);

                if (is_array($assignment->value) && $assignment->property->isMultiple()) {
                    $assignment->value = array_filter(array_map(static function ($data) {
                        return (int)$data;
                    }, $assignment->value));
                    $values = $assignment->value;
                    foreach ($values as $value) {
                        $assignmentsForInsert[] = new BlockItemPropertyAssignments([
                            'property_id' => $assignment->property_id,
                            'block_item_id' => $assignment->block_item_id,
                            'value' => $value,
                        ]);
                    }
                } elseif ($assignment->property->isFile()) {
                    // if empty value
                    $assignment->value = $assignment->value ? explode(';', $assignment->value) : [];
                    foreach ($assignment->value as $value) {
                        $assignmentsForInsert[] = new BlockItemPropertyAssignments([
                            'property_id' => $assignment->property_id,
                            'block_item_id' => $assignment->block_item_id,
                            'value' => $value,
                        ]);
                    }
                } else {
                    $assignmentsForInsert[] = new BlockItemPropertyAssignments([
                        'property_id' => $assignment->property_id,
                        'block_item_id' => $assignment->block_item_id,
                        'value' => $assignment->value,
                    ]);
                }
//                $validate = !$assignment->validate() ? false : $validate;
                $assignment->validate() or die(var_dump($assignment->getErrors()));
            }
        }

//        $propertyAssignmentsInsert = array_filter($assignments, static function ($data) {
//            return $data->value !== '' || !$data->getIsNewRecord();
//        });

        $this->propertyAssignmentsInsert = $assignmentsForInsert;

        return $validate;
    }

    public function beforeValidate()
    {
        $settings = array_merge(Yii::$app->params['blockItem'], Yii::$app->params['blockItem' . $this->block_id] ?? []);

        $this->attachBehaviors([
            'photo_preview' => [
                'class' => UploadImageBehavior::class,
                'attributeName' => 'photo_preview',
                'cropCoordinatesAttrName' => 'photo_preview_crop',
                'savePath' => $settings['photo_preview']['dir'],
                'dir' => $settings['photo_preview']['urlDir'],
                'defaultCrop' => $settings['photo_preview']['defaultCrop'],
                'crop' => $settings['photo_preview']['crop'],
                'generateNewName' => true,
            ],
            'photo' => [
                'class' => UploadImageBehavior::class,
                'attributeName' => 'photo',
                'cropCoordinatesAttrName' => 'photo_crop',
                'savePath' => $settings['photo']['dir'],
                'dir' => $settings['photo_preview']['urlDir'],
                'defaultCrop' => $settings['photo']['defaultCrop'],
                'crop' => $settings['photo']['crop'],
                'generateNewName' => true,
            ]
        ]);

        return parent::beforeValidate();
    }

    public function getAssignmentsByPropertyId($propertyId)
    {
        return $this->propertyAssignmentsUpdate[$propertyId] ?? null;
    }

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => SlugBehavior::class,
                'in_attribute' => 'title',
                'out_attribute' => 'alias'
            ],
        ];
    }

    public function rules()
    {
        return [
            [['title', 'block_id', 'sort'], 'required'],
            [['anons', 'text'], 'string'],
            [['date', 'create_date', 'update_date'], 'safe'],
            [['block_id', 'section_id', 'public', 'sort', 'create_user', 'update_user'], 'integer'],
            [['title', 'alias', /*'photo', 'photo_preview',*/ 'photo_crop', 'photo_preview_crop', 'seo_title', 'seo_keywords', 'seo_description'], 'string', 'max' => 255],
            [['photo', 'photo_preview'], 'file', 'mimeTypes' => 'image/*'],
            [['section_id'], 'default', 'value' => 0],
        ];
    }
}