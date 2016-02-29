<?php
namespace app\ext\treeMenuWidget;

use yii\db\Query;
//use yii\helpers\VarDumper;
use yii\widgets\Menu;
/**
 * Created by PhpStorm.
 * User: KVUSH-NBOOK
 * Date: 26.10.2015
 * Time: 3:54
 *
 * Нужна настройка подключения к БД в конфигурации приложения чтобы иметь доступ через yii::$app->db
 */
class TreeMenu extends Menu
{
    private $catItem;
    private $underGroupItem;
    private $fullMenuItem;

    private function getTopGroup(){
        return (new Query())
            ->from('fsh_groupofgroups')
            ->orderBy('sort ASC')
            ->all();
    }
    
    private function getGroup($topgroup_id){
        return (new Query())
            ->from('fsh_groupofcat')
            ->where('FIND_IN_SET(:id, topgroup_id)',[':id'=>$topgroup_id])
            ->orderBy('sort ASC')
            ->all();
    }
    
    private function getGroupWithNoTopGroup(){
        return (new Query())
            ->from('fsh_groupofcat')
            ->where('topgroup_id is NULL')
            ->orderBy('sort ASC')
            ->all();
    }

    private function getItemsWithNoGroup(){
        return (new Query())
            ->from('fsh_catalog')
            ->where('group_id is NULL')
            ->orderBy('sort ASC')
            ->all();
    }

    public function getMenuItems(){
        $this->fullMenuItem=[];        
        $topGrArr = $this->getTopGroup();
        
        foreach($topGrArr as $topGrOne){
            $this->underGroupItem = []; // unset array before each cycle
            $grArr=$this->getGroup($topGrOne['id']);
            
            foreach ($grArr as $grOne){
                $this->catItem = []; // unset array before each cycle
                $catUnderGr = (new Query())
                    ->select('id, name, slug')
                    ->from('fsh_catalog')
                    ->where('FIND_IN_SET(:id, group_id)',[':id'=>$grOne['id']])
                    ->orderBy('sort ASC')
                    ->all();
                //echo 'here '.VarDumper::dump($catUnderGr);

                foreach ($catUnderGr as $catOne){
                    $_catItem=[
                        'label'=>$catOne['name'],
                        'url'=>['/'.$topGrOne['slug'].'/'.$grOne['slug'].'/'.$catOne['slug']],
                        'options' => ['id'=>'cat'.$catOne['id'], 'class'=>'goAjax'],
                    ];
                    $this->catItem[]=$_catItem;
                }

                $_underGroupItem = [
                    'label'=>$grOne['name'],
                    'url'=>array('#'),
                    'options' => ['id'=>'group'.$grOne['id']],
                    'items' => $this->catItem,
                ];
                $this->underGroupItem[]=$_underGroupItem;
            }
            
            $_fullMenuItem = [
                    'label'=>$topGrOne['name'],
                    'url'=>array('#'),
                    'options' => ['id'=>'topGroup'.$topGrOne['id']],
                    'items' => $this->underGroupItem,
                    'submenuTemplate' => "\n<ul class=\"tree-group-collapsed\">\n{items}\n</ul>\n",
                    'template' => '<a class="tree-parent-collapsed" href="{url}">{label}</a>',
                ];
            $this->fullMenuItem[]=$_fullMenuItem;
        }
        /* --------------------------------------------
         * Finish TopGroup
         *
         * Starting Group which are not grouped in Top Group
         * */
        /*$this->fullMenuItem[] = [
            'label'=>'<br>',
        ];*/
        /* -------------------------------------------- */
        $noTopGroupArr = $this->getGroupWithNoTopGroup();
        foreach ($noTopGroupArr as $noTopGroupOne){
                $this->catItem = []; // unset array before each cycle
                $catUnderGr = (new Query())
                    ->select('id, name, slug')
                    ->from('fsh_catalog') //допил для двух разделов каталога (светотехника и электротехника)
                    ->where('FIND_IN_SET(:id, group_id)',[':id'=>$noTopGroupOne['id']])
                    ->orderBy('sort ASC')
                    ->all();
                //echo 'here '.VarDumper::dump($catUnderGr);

                foreach ($catUnderGr as $catOne){
                    $_catItem=[
                        'label'=>$catOne['name'],
                        'url'=> ['/products/index', 'grName' =>$noTopGroupOne['slug'], 'catName' => $catOne['slug']],
                        'options' => ['id'=>'cat'.$catOne['id'], 'class'=>'goAjax'],
                    ];
                    $this->catItem[]=$_catItem;
                }

                $_underGroupItem = [
                    'label'=>$noTopGroupOne['name'],
                    'url'=>array('#'),
                    'options' => ['id'=>'group'.$noTopGroupOne['id']],
                    'items' => $this->catItem,
                    'submenuTemplate' => "\n<ul class=\"tree-group-collapsed\">\n{items}\n</ul>\n",
                    'template' => '<a class="tree-parent-collapsed" href="{url}">{label}</a>',
                ];
                $this->fullMenuItem[]=$_underGroupItem;
            }
        
        
        
        
        /* --------------------------------------------
         * Finish Group
         *
         * Starting category which are not grouped
         * */
        $this->fullMenuItem[] = [
            'label'=>'<br>',
        ];
        /* -------------------------------------------- */

        $noGroupArr = $this->getItemsWithNoGroup();
        foreach ($noGroupArr as $noGroupOne){
            $_item = [
                'label'=>$noGroupOne['name'],
                'url'=> ['/products/index', 'catName' => $noGroupOne['slug']],
                'options' => ['id'=>'cat'.$noGroupOne['id'], 'class'=>'goAjax'],
            ];
            $this->fullMenuItem[]=$_item;
        }

        return $this->fullMenuItem;
    }

    public function run(){
        $this->items = $this->getMenuItems();
        $this->options =['id'=>'catalog_menu', 'class' => 'tree'];
        $this->encodeLabels = false;
        TreeMenuAsset::register($this->view);
        parent::run();
    }
}