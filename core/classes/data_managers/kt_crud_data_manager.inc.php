<?php

class KT_CRUD_Data_Manager extends KT_Data_Manager_Base {
    
    private $modelName = null;
    private $repository = null;
    private $keyColumn = null;
    private $labelColumn = null;
    
    /**
     * 
     * @param type $modelName
     * @param type $table
     * @return \KT_CRUD_Data_Manager
     */
    public function __construct($modelName, $keyColumnName, $labelColumnName, $table = null) {
        $this->setModelName($modelName)
                ->setKeyColumn($keyColumnName)
                ->setLabelColumn($labelColumnName)
                ->respositoryInit($table);
        
        return $this;
    }
    
    // --- gettery a settery ------------------
    
    public function getData(){
        if(kt_not_isset_or_empty(parent::getData())){
            $this->initData();
        }
        
        return parent::getData();
    }

    /**
     * @return string
     */
    private function getModelName() {
        return $this->modelName;
    }

    /**
     * Nastaví název třídy (modelu), který se bude v rámci manageru selektovat
     * 
     * @param string $modelName
     * @return \KT_CRUD_Data_Manager
     */
    private function setModelName($modelName) {
        $this->modelName = $modelName;
        return $this;
    }
    
    /**
     * @return \KT_Repository
     */
    public function getRepository() {
        return $this->repository;
    }

    /**
     * Nastaví repositář s daným CRUD objektem pro práci se selekcí dat
     * 
     * @author Tomáš Kocifaj
     * @link www.ktstudio.cz
     * 
     * @param KT_Repository $repository
     * @return \KT_CRUD_Data_Manager
     */
    private function setRepository(KT_Repository $repository) {
        $this->repository = $repository;
        return $this;
    }
    
    /**
     * @return string
     */
    private function getKeyColumn(){
        return $this->keyColumn;
    }
    
    /**
     * Nastaví, který sloupec v rámci CRUD má být dostupný v hodnotě value u options
     * 
     * @author Tomáš Kocifaj
     * @link www.ktstudio.cz
     * 
     * @param string $keyColumn
     * @return \KT_CRUD_Data_Manager
     */
    public function setKeyColumn($keyColumn) {
        $this->keyColumn = $keyColumn;
        return $this;
    }
    
    /**
     * @return string
     */
    private function getLabelColumn(){
        return $this->labelColumn;
    }

    /**
     * Nastaví, který sloupec v rámci CRUD má být dostupný jako label (popisek) u options
     * 
     * @author Tomáš Kocifaj
     * @link www.ktstudio.cz
     * 
     * @param string $labelColumn
     * @return \KT_CRUD_Data_Manager
     */
    public function setLabelColumn($labelColumn) {
        $this->labelColumn = $labelColumn;
        return $this;
    }
    
    // --- privátní funkce ------------------
    
    /**
     * Na základě modelu (případně i tabulky) provede inicializaci KT_Repository
     * a uloží ho do modelu pro další práci.
     * 
     * @author Tomáš Kocifaj
     * @link www.ktstudio.cz
     * 
     * @param string $table
     * @return \KT_CRUD_Data_Manager
     */
    private function respositoryInit($table = null){
        
        $modelName = $this->getModelName();
        
        if(kt_not_isset_or_empty($table)){
            $table = $modelName::TABLE;
        }
        
        $repository = new KT_Repository($modelName, $table);
        $this->setRepository($repository);
        
        return $this;
    }
    
    /**
     * Provede inicializaci dat na základě nastaveného repositáře.
     * 
     * @author Tomáš Kocifaj
     * @link www.ktstudio.cz
     * 
     * @return \KT_CRUD_Data_Manager
     * @throws KT_Not_Set_Argument_Exception
     */
    private function initData(){
        
        $items = $this->getRepository()->selectData()->getItems();
        $keyColumnName = $this->getKeyColumn();
        $labelColumnName = $this->getLabelColumn();
        
        if(kt_not_isset_or_empty($keyColumnName)){
            throw new KT_Not_Set_Argument_Exception("keyColumnName is not set");
        }
        
        if(kt_not_isset_or_empty($labelColumnName)){
            throw new KT_Not_Set_Argument_Exception("labelColumnName is not set");
        }
        
        if(kt_not_isset_or_empty($items)){
            $this->setData(array(KT_EMPTY_TEXT));
            return this;
        }
        
        foreach($items as $item){
            $optionsData[$item->$keyColumnName] = $item->$labelColumnName;
        }
        
        $this->setData($optionsData);
        
        return $this;
       
    }

}