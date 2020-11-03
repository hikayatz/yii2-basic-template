<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_menu".
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string|null $label
 * @property string|null $url
 * @property string|null $description
 * @property string|null $icon
 * @property int|null $menu_level
 * @property float|null $menu_order
 * @property int|null $menu_status
 * @property string|null $updated_at
 *
 * @property AuthMenu[] $authMenus
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'm_menu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'menu_level', 'menu_status'], 'default', 'value' => null],
            [['parent_id', 'menu_level', 'menu_status'], 'integer'],
            [['menu_order'], 'number'],
            [['updated_at'], 'safe'],
            [['label', 'icon'], 'string', 'max' => 100],
            [['url'], 'string', 'max' => 150],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'label' => 'Label',
            'url' => 'Url',
            'description' => 'Description',
            'icon' => 'Icon',
            'menu_level' => 'Menu Level',
            'menu_order' => 'Menu Order',
            'menu_status' => 'Menu Status',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[AuthMenus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthMenus()
    {
        return $this->hasMany(AuthMenu::className(), ['menu_id' => 'id']);
    }

    public static function getMenuTree()
    {
        $rows = self::find()->orderBy(["menu_order" => SORT_ASC])->all();
        $ref = [];
        $items = [];
        foreach ($rows as $index => $data) {
            $thisRef = &$ref[$data->id];
            $thisRef['parent'] = $data->parent_id;
            $thisRef['label'] = $data->label;
            $thisRef['icon'] = $data->icon;
            $thisRef['description'] = $data->description;
            $thisRef['link'] = $data->url;
            $thisRef['id'] = $data->id;

            if ($data->parent_id == 0) {
                $items[$data->id] = &$thisRef;
            } else {
                $ref[$data->parent_id]['child'][$data->id] = &$thisRef;
            }
        }
        return $items;
    }
}