<?php
/**
* Omeka
* 
* @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
* @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
*/
/**
* @package Omeka\Controller
*/
class Glossary_IndexController extends Omeka_Controller_AbstractActionController
{
    public function init() 
    {
        $this->_helper->db->setDefaultModelName('Item');
    }
    public function indexAction()
    {
        // Respect only GET parameters when browsing.
        $this->getRequest()->setParamSources(array('_GET'));
        // Inflect the record type from the model name.
        $pluralName = $this->view->pluralize($this->_helper->db->getDefaultModelName());
        
        $params = $this->getAllParams();
        $params['type'] = 'Glossary';
      
        // Get the records filtered to Omeka_Db_Table::applySearchFilters().
        $records = $this->_helper->db->findBy($params);
        $totalRecords = $this->_helper->db->count($params);
        
        $this->view->assign(array($pluralName => $records, 'total_results' => $totalRecords));
    }

}
