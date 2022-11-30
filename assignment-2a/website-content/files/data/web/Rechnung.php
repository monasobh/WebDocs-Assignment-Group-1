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


class Rechnung extends DataObject
{

	private static $singular_name = "Rechnung";
	private static $plural_name = "Rechnung";

	private static $extensions = [
        Versioned::class
    ];

	private static $db = [
		'Rechnungsnummer' => 'Varchar(50)', // wie Auftragsnummer und .1, .2 usw. A oder ohne
		'Typ' => 'Varchar(255)',
		'Kunde' => 'Varchar(255)',
		'Title' => 'Varchar(255)',
		'AngelegtVon' => 'Varchar(255)',
		'Status' => 'Varchar(255)',
		'RechnungVersandtAm' => 'Date',
		'RechnungBezahltAm' => 'Date',
		'Content' => 'HTMLText',
		'LastMember' => 'Int',
		'SortOrder' => 'Int'
	];

 	private static $has_one = [
	 	'Auftrag' => Auftrag::class
    ];

	private static $has_many = [

	];

    private static $many_many = [
		'DownloadFile' => File::class
	];


    private static $owns = [
        'DownloadFile'
    ];


	private static $summary_fields = [
  		'Rechnungsnummer' => 'Auftragsnummer',
  		'Typ' => 'Typ',
  		'Kunde' => 'Kunde'
 	];


 	public function getCMSFields() {

 		if($this->AngelegtVon == ''){
	 		$this->AngelegtVon = Member::currentUser()->Email;
	 	}

 		$fields = new FieldList(
			DropdownField::create('Typ', 'Typ', array(
				'a-conto' => 'A Conto',
				'Rechnung' => 'Rechnung'
			)),

			TextField::create('Kunde', 'Kunde'),
	   		TextField::create('Auftragsnummer', 'Auftragsnummer'),
	   		TextField::create('Title', 'Titel'),
	   		TextField::create('AngelegtVon', 'Angelegt von'),

			DropdownField::create('Status', 'Status', array(
				'noStatus' => 'Kein Status',
				'versandt' => 'Versandt',
				'storniert' => 'storniert'
			)),

			DateField::create('RechnungVersandtAm', 'Rechnung versandt am:'),
			DateField::create('RechnungBezahltAm', 'Rechnung bezahlt am:'),

			HtmlEditorField::create(
				'Content', 'Notizen'
			),
	   		TextField::create('Kunde', 'Kunde'),
	   	);

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
