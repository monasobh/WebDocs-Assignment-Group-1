<?php
use SilverStripe\Versioned\Versioned;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Member;

use TractorCow\Fluent\State\FluentState;

use SilverStripe\View\Requirements;
use Silverstripe\SiteConfig\SiteConfig;

use SilverStripe\Forms\FieldList;

use SilverStripe\Forms\DateField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\CheckboxField;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\TreeDropdownField;

use SilverStripe\Forms\LiteralField;


use SilverStripe\Assets\Image;
use SilverStripe\Assets\File;
use SilverStripe\AssetAdmin\Forms\UploadField;


use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\GridField\GridFieldToolbarHeader;
use SilverStripe\Forms\GridField\GridField_ActionProvider;
use SilverStripe\Forms\GridField\GridField_FormAction;
use SilverStripe\Forms\GridField\GridFieldDataColumns;

use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;


class Auftrag extends DataObject
{

	private static $singular_name = "Auftrag";
	private static $plural_name = "Auftrag";

	private static $extensions = [
        Versioned::class
    ];

	private static $db = [
		'Auftragsnummer' => 'Varchar(50)', // beginnt mit Jahr 22101 (erste)
		'Kunde' => 'Varchar(255)',
		'Title' => 'Varchar(255)',
		'AngelegtVon' => 'Varchar(255)',
		'Status' => 'Varchar(255)',
		'AngebotVersandtAm' => 'Date',
		'Content' => 'HTMLText',
		'LastMember' => 'Int'
	];

 	private static $has_one = [
	 	
    ];

	private static $has_many = [
		
	];

    private static $many_many = [
		'DownloadFile' => File::class,
		'Rechnung' => Rechnung::class
	];


    private static $owns = [
        'DownloadFile',
        'Rechnung'
    ];


	private static $summary_fields = [
  		'Auftragsnummer' => 'Auftragsnummer',
  		'Title' => 'Titel',
  		'Kunde' => 'Kunde'
 	];


 	public function getCMSFields() {
 		
 		if($this->AngelegtVon == ''){
	 		$this->AngelegtVon = Member::currentUser()->Email;
	 	}
 		
 		$fields = new FieldList(
	   		TextField::create('Auftragsnummer', 'Auftragsnummer'),
	   		TextField::create('Title', 'Titel'),
	   		TextField::create('AngelegtVon', 'Angelegt von'),
	   		TextField::create('Kunde', 'Kunde'),
	   	);

		$gridField = GridField::create(
		        'Rechnung',
		        'Rechnungen',
		        $this->Rechnung(),
		        GridFieldConfig_RecordEditor::create()
	        );

	        $gridField->setConfig(
		        GridFieldConfig_RelationEditor::create()
	        );

	        $config = $gridField->getConfig()->addComponents(
		        new GridFieldSortableRows('SortOrder')
	        );
	        $gridField->getConfig()->removeComponentsByType(GridFieldDeleteAction::class);

	        $dataColumns = $config->getComponentByType(GridFieldDataColumns::class);

	        $dataColumns->setDisplayFields([
	            'Title' => 'Titel',
	            'rawContent' => 'Inhalt',
		  		'Thumbnail' => 'Bild'
	        ]);

	        $fields->addFieldToTab("Root.Main", $gridField);
	   	
	   	return $fields;
 		
	}

	public function onBeforeWrite() 
    {
        
        $this->LastMember = Member::currentUser()->ID;
        parent::onBeforeWrite();
        
    }



	public function canView($member = null)
    {
        return Permission::check('CMS_ACCESS_AuftragAdmin', 'any', $member);
    }

    public function canEdit($member = null)
    {
        return Permission::check('CMS_ACCESS_AuftragAdmin', 'any', $member);
    }

    public function canDelete($member = null)
    {
        return Permission::check('CMS_ACCESS_AuftragAdmin', 'any', $member);
    }

    public function canCreate($member = null, $context = [])
    {
        return Permission::check('CMS_ACCESS_AuftragAdmin', 'any', $member);
    }
}
?>
