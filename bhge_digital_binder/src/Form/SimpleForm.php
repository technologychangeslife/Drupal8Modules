<?php
namespace Drupal\bhge_digital_binder\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;
use Drupal\Core\Url;
use Drupal\Component\Utility\UrlHelper;
use Symfony\Component\HttpFoundation\RedirectResponse;


 
/**
* Implements a simple form.
*/
class SimpleForm extends FormBase {
  
  
  /**
  * Build the simple form.
  *
  * @param array $form
  *   Default form array structure.
  * @param FormStateInterface $form_state
  *   Object containing current form state.
  */
  public function buildForm(array $form, FormStateInterface $form_state) {
      
    $document_node = \Drupal\node\Entity\Node::load(23941);
    $dam_field_file = $document_node->get('field_dam_file')->getValue();
          //$dam_field_file = $node->get('field_dam_file')->getValue();
          /*$media = Media::load($dam_field_file[0]['target_id']);
          $media_field_asset = $media->get('field_asset')->getValue();
          $file = File::load($media_field_asset[0]['target_id']);
          $dam_file_uri = $file->getFileUri();
          $dam_url = file_create_url($dam_file_uri);*/
          //$dam_url = getfileurl($dam_field_file[0]['target_id']);
          
          
          //print_r($field_dam_file);
    $form['document_title'] = array(
      '#type' => 'textfield',
      '#title' => t('Search with document title'),
      '#required' => FALSE,
    );
    
    
    
    
    $query = \Drupal::entityQuery('node')
        ->condition('status', 1) //published or not
        ->condition('type', 'section'); //content type
        //->pager(100); //specify results to return
        $nids = $query->execute();
    foreach ($nids as $nid) {
          $node = \Drupal\node\Entity\Node::load($nid); 
          $body = $node->body->value;
          $document_query1 = \Drupal::entityQuery('node');
        $document_query1->condition('status', 1); //published or not
        $document_query1->condition('type', 'document'); //content types
        $document_query1->condition('field_language', '451');
        
        if(!empty($nid)) {
          $document_query1->condition('select_brand', $nid, 'IN'); // filtering brands
        }
        
         $count = $document_query1->count()->execute();
         $node->title->value;
        //print "<br>";

         if($count>0) {
            // print $node->title->value;
          //print "<br>";
          if($node->title->value == 'Masoneilan' || $node->title->value == 'Consolidated' || $node->title->value == 'Mooney' || $node->title->value == 'Becker') {
            $nid;
            $title = $node->title->value; //print '<br>';
            $brands[$nid] = $title."(".$count.")";
          }
         }
        }
        
        # the drupal checkboxes form field definition
        $form['brands'] = array(
          '#title' => t('Select Brands'),
          '#type' => 'checkboxes',
          '#description' => t('Select the brands you want.'),
          '#options' => $brands,
          '#required' => FALSE,
        );
        
        foreach ($nids as $nid) {
          $node = \Drupal\node\Entity\Node::load($nid); 
          $body = $node->body->value;
          $document_query2 = \Drupal::entityQuery('node');
        $document_query2->condition('status', 1); //published or not
        $document_query2->condition('type', 'document'); //content types
        $document_query2->condition('field_language', '451');
          
          if(!empty($nid)) {
           $document_query2->condition('field_select_product', $nid, 'IN'); // filtering brands
          }
          $count2 = $document_query2->count()->execute();
          if($count2>0) {
           $node->title->value;
           //print "<br>";
           if($node->title->value != 'Masoneilan' || $node->title->value != 'Consolidated' || $node->title->value != 'Mooney' || $node->title->value != 'Becker') {
             $nid;
             $title = $node->title->value; //print '<br>';
            
             $product_types_array[$nid] = $title."(".$count2.")";
           }
          }
        }

              /*$product_types_array = array(
              '376' => t('Regulators'),
              '361' => t('Safety Relief Valves'), 
              '331' => t('Software Tools'),
              '346' => t('Level Transmitters/Controllers'),
            );*/
            
        $document_query_mn = \Drupal::entityQuery('node');
        $document_query_mn->condition('status', 1); //published or not
        $document_query_mn->condition('type', 'document'); //content types
        $document_query_mn->condition('field_language', '451');
        $document_query_mn->sort('created' , 'DESC');
        $nids_mn = $document_query_mn->execute();
        foreach ($nids_mn as $nid_mn) {
         $document_node = \Drupal\node\Entity\Node::load($nid_mn);
         $field_part_number = $document_node->get('field_part_number')->getValue();
         $field_select_product = $document_node->get('field_select_product')->getValue();
         //print'<pre>'; print_r($field_select_product); print'</pre>';
         $product_type_for_this_model = '';
         foreach($field_select_product as $key => $value) {
             $pid = $field_select_product[$key]['target_id']; //print '<br>';
             $product_node = \Drupal\node\Entity\Node::load($pid);
             //print $product_node->title->value; print '<br>';
             $product_type_for_this_model = $product_node->title->value.','.$product_type_for_this_model;
         }
         //print_r($field_part_number); exit();
         //print'<pre>'; print_r($field_part_number); print'</pre>';
         foreach($field_part_number as $k => $val) {
             //print'<pre>'; print_r($val); print'</pre>';
             //print $val['value']; print '<br>';
             //foreach() {
             
               $mystring = $val['value'];
               $findme   = 'GEA';
               $findme2   = 'gea';
               $pos = strpos($mystring, $findme);
               $pos2 = strpos($mystring, $findme2);
               //print $k; print '<br>';
               //print $v; print '<br>';
               if ($pos === false && $pos2 === false) {
                 $model_number[$mystring] = $mystring.'('.$product_type_for_this_model.')';
               }
             //}
         }
        }
        
        //print_r($model_number);
    
        # the drupal checkboxes form field definition
        $form['model_number'] = array(
          '#title' => t('Select Model Number'),
          '#type' => 'select',
          '#description' => t('Select the Model Number you want.'),
          '#options' => $model_number,
          '#multiple' => TRUE,
          '#prefix' => "<div id='model-number-wrapper'>",
          '#suffix' => '</div>',
          '#required' => FALSE,
        );
        
           /*$form['model_number'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Model Number'),
      '#prefix' => "<div id='model-number-wrapper'>",
      '#suffix' => '</div>',
    ];*/
    
        $form['ajaxres'] = [
         '#type' => 'markup',
         '#markup' => '<div class="res">Your 1st page load</div>',
         '#required' => FALSE,
        ];
        

        # the drupal checkboxes form field definition
        $form['product_types'] = array(
        '#title' => t('Select Product Types'),
        '#type' => 'checkboxes',
        '#description' => t('Select the product types you want.'),
        '#options' => $product_types_array,
        '#ajax' => [
          'callback' => '::filter_model_number',
          'wrapper' => 'model-number-wrapper',
         ],
         '#required' => FALSE,
        );
        
        $vid = 'documents_topic';
        $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
        foreach ($terms as $term) {
         $document_query3 = \Drupal::entityQuery('node');
        $document_query3->condition('status', 1); //published or not
        $document_query3->condition('type', 'document'); //content types
        $document_query3->condition('field_language', '451');
            
        if(!empty($nid)) {
          $document_query3->condition('field_topic', $term->tid, 'IN'); // filtering brands
        }
        
         $count3 = $document_query3->count()->execute();
         if($count3) {
          if($term->name == 'Catalog' || $term->name == 'Brochures' || $term->name == 'Tech Spec/Fact Sheet' || $term->name == 'Manual' || $term->name == 'Articles and Case Studies') {
           $term_name = $term->name;
           $term_data[$term->tid] = $term_name."(".$count3.")";
          }
         }
       }
       
       # the drupal checkboxes form field definition
       $form['file_types'] = array(
        '#title' => t('Select File Types'),
        '#type' => 'checkboxes',
        '#description' => t('Select the file types you want.'),
        '#options' => $term_data,
        '#required' => FALSE,
       );

     
   

    /*$form['number_1'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Number 1'),
    ];

    $form['number_2'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Number 2'),
    ];*/

    $form['actions_search'] = [
      '#type' => 'button',
      '#value' => $this->t('Search'),
      '#ajax' => [
        'callback' => '::setMessage',
        'wrapper' => 'search-result-wrapper',
      ],
    ];
    
    /*$form['search_result'] = [
      '#type' => 'select',
      '#markup' => '<div class="result_message">Your 1st page load</div>',
      '#prefix' => "<div id='search-result-wrapper'>",
          '#suffix' => '</div>',
    ];*/
    
    $model_number_array = array(
              376 => t('Regulators'),
              361 => t('Safety Relief Valves'), 
              331 => t('Software Tools'),
              346 => t('Level Transmitters/Controllers'),
            );
    
    # the drupal checkboxes form field definition
        $form['search_result'] = array(
          '#title' => t('Your search result'),
          '#type' => 'select',
          '#description' => t('Your search result desc.'),
          //'#options' => $model_number_array,
          '#multiple' => TRUE,
          '#prefix' => "<div id='search-result-wrapper'>",
          '#suffix' => '</div>',
          //'#ajax' => [
            //'callback' => '::selected_documents',
            //'wrapper' => 'selected-document-wrapper',
          //],
          '#required' => FALSE,
        );
        
        $form['selected_documents'] = array(
          '#title' => t('Your selected documents'),
          '#type' => 'select',
          '#multiple' => TRUE,
          //'#markup' => '<div class="selected_document_wrapper">Your 1st page load</div>',
          '#prefix' => "<div id='selected-document-wrapper'>",
          '#suffix' => '</div>',
          '#required' => FALSE,
        );
        
        //$form['actions']['#type'] = 'actions';
        $form['submit'] = array(
         '#type' => 'submit',
         '#value' => $this->t('Submit'),
        );
    

    return $form;
  }
  
  public function selected_documents(array &$form, FormStateInterface $form_state) {
      $search_result = $form_state->getValue('search_result'); //print '<br>';
      //print_r($search_result); //exit();
      $response = new AjaxResponse();
      $response->addCommand(
          new HtmlCommand(
          '.selected_document_wrapper',
          '<div class="my_top_message_res"> Ajax Responding </div>')
        );
      $selected_docs = array();
        foreach($search_result as $key => $value) {
            if($key == $value) {
              //array_push($selected_docs ,$value);
              $selected_docs[$key] = $value;
            }
      }        
      //print_r($selected_docs);
      
      $form['selected_documents']['#options'] = $selected_docs;

      
      
      return $form['selected_documents'];
  }
  
  public function filter_model_number(array &$form, FormStateInterface $form_state) {
      $response = new AjaxResponse();
      
        $product_types_array = $form_state->getValue('product_types'); //print '<br>';
        //print_r($product_types_array);
        $products_filter = array();
        foreach($product_types_array as $key => $value) {
            if($key == $value) {
              array_push($products_filter ,$value);
            }
        }    
        $response->addCommand(
          new HtmlCommand(
          '.res',
          '<div class="my_top_message_res"> Ajax Responding </div>')
        );  
        //return $response;
        
        $field_select_product_filter = $products_filter;
        
        $document_query = \Drupal::entityQuery('node');
        $document_query->condition('status', 1); //published or not
        $document_query->condition('type', 'document'); //content types
        $document_query->condition('field_language', '451'); // Only English docs required        
        if(!empty($field_select_product_filter)) {
          $document_query->condition('field_select_product', $field_select_product_filter, 'IN'); // filtering product types
        }
        
        
        $document_query->sort('created' , 'DESC');
        
        //$document_query->pager(50); //specify results to return    
        $document_nids = $document_query->execute();
        
        
        $response = new AjaxResponse();        
        $document_title = '';
        $x = 1;

        foreach ($document_nids as $document_nid) {
          $document_node = \Drupal\node\Entity\Node::load($document_nid);
          $field_part_number = $document_node->get('field_part_number')->getValue();
          $field_select_product = $document_node->get('field_select_product')->getValue();
         //print'<pre>'; print_r($field_select_product); print'</pre>';
         $product_type_for_this_model = '';
         foreach($field_select_product as $key => $value) {
             $pid = $field_select_product[$key]['target_id']; //print '<br>';
             $product_node = \Drupal\node\Entity\Node::load($pid);
             //print $product_node->title->value; print '<br>';
             $product_type_for_this_model = $product_node->title->value.','.$product_type_for_this_model;
         }
         //print_r($field_part_number); exit();
         
         foreach($field_part_number as $k => $val) {
             
           $mystring = $val['value'];;
           $findme   = 'GEA';
           $findme2   = 'gea';
           $pos = strpos($mystring, $findme);
           $pos2 = strpos($mystring, $findme2);
           //print $k; print '<br>';
           //print $v; print '<br>';
           if ($pos === false && $pos2 === false) {
             $model_number[$mystring] = $mystring.'('.$product_type_for_this_model.')';
           }
         }  
          
        }
        
     $model_number_array = array(
              '376' => t('Regulators'),
              '361' => t('Safety Relief Valves'), 
              '331' => t('Software Tools'),
              '346' => t('Level Transmitters/Controllers'),
            );
              $form['model_number']['#options'] = $model_number;

      //return $response;
      
      return $form['model_number'];
      
              //$commands = array();
                      //$commands[] = ajax_command_replace('#model-number-wrapper', render($form['model_number']));
    
      
              //$commands[] = ajax_command_replace('#title-wrapper', render($form['title']));
              
                      //return array('#type' => 'ajax', '#commands' => $commands);


  }
  
  /**
   *
   */
  public function setMessage(array $form, FormStateInterface $form_state) {
      
    $document_title = $form_state->getValue('document_title'); //print '<br>';
    
    $model_number = $form_state->getValue('model_number'); //print '<br>';
    
    $brands = $form_state->getValue('brands'); //print '<br>';
    //print_r($brands);
    $brands_filter = array();
    foreach($brands as $key => $value) {
        if($key == $value) {
          array_push($brands_filter ,$value);
        }
    }
    
    $product_types_array = $form_state->getValue('product_types'); //print '<br>';
    //print_r($product_types_array);
    $products_filter = array();
    foreach($product_types_array as $key => $value) {
        if($key == $value) {
          array_push($products_filter ,$value);
        }
    }
    
    $file_types = $form_state->getValue('file_types'); //print '<br>';
    //print_r($file_types);
    $files_filter = array();
    foreach($file_types as $key => $value) {
        if($key == $value) {
          array_push($files_filter ,$value);
        }
    }
    
    $model_number = $form_state->getValue('model_number');
    $document_title = $form_state->getValue('document_title');
    $select_brand_filter = $brands_filter; 
    //print_r($select_brand_filter);
    $field_select_product_filter = $products_filter;
    //print_r($field_select_product_filter);
    $field_topic_filter = $files_filter;
    //print_r($field_topic_filter);
    $field_part_number_filter = $model_number;
        
        $document_query = \Drupal::entityQuery('node');
        $document_query->condition('status', 1); //published or not
        $document_query->condition('type', 'document'); //content types
        $document_query->condition('field_language', '451'); // Only English docs required
        
            
        if(!empty($document_title)) {
           $document_query->condition('title', $document_title, 'CONTAINS'); //content title
        }
        if(!empty($select_brand_filter)) {
          $document_query->condition('select_brand', $select_brand_filter, 'IN'); // filtering brands
        }
        if(!empty($field_select_product_filter)) {
          $document_query->condition('field_select_product', $field_select_product_filter, 'IN'); // filtering product types
        }
        if(!empty($field_topic_filter)) {
          $document_query->condition('field_topic', $field_topic_filter, 'IN'); // filtering file types
        }
        if(!empty($field_part_number_filter)) {
          $document_query->condition('field_part_number', $field_part_number_filter, 'IN'); // filtering file types
        }
        
        $document_query->sort('created' , 'DESC');
        //$document_query->pager(50); //specify results to return    
        $document_nids = $document_query->execute();
        
        
        $response = new AjaxResponse();        
        $document_title = '';
        $x = 1;

        foreach ($document_nids as $document_nid) {
          $document_node = \Drupal\node\Entity\Node::load($document_nid);
          
          $dt = $document_node->title->value;
          $dam_field_file = $document_node->get('field_dam_file')->getValue();
          //$dam_field_file = $node->get('field_dam_file')->getValue();
          /*$media = Media::load($dam_field_file[0]['target_id']);
          $media_field_asset = $media->get('field_asset')->getValue();
          $file = File::load($media_field_asset[0]['target_id']);
          $dam_file_uri = $file->getFileUri();
          $dam_url = file_create_url($dam_file_uri);*/
          //$dam_url = getfileurl($dam_field_file[0]['target_id']); // get the file url
          $document_title = $x.':-'.$dt.'  <a href="#'.$dam_url.'">Add</a><div>'.$document_title.' </div></br>'; //print '<br>';
          $search_results_items[$document_nid] = $document_node->title->value;
          
          
          $field_topic = $document_node->get('field_topic')->getValue(); //print '<br>';
          //print '<pre>'; print_r($field_topic); print '<pre>'; //exit();
          
          $field_select_product = $document_node->get('field_select_product')->getValue(); //print '<br>';
          //print '<pre>'; print_r($field_select_product); print '<pre>'; //exit();
          
          $select_brand = $document_node->get('select_brand')->getValue(); //print '<br>';
          //print '<pre>'; print_r($select_brand); print '<pre>';
          
          $field_part_number = $document_node->get('field_part_number')->getValue(); //print '<br>';
          //print '<pre>'; print_r($field_part_number); print '<pre>';
          
          //print '----------------';
          //print '<br>';
          
          $x = $x + 1;
        }
        
        $response->addCommand(
          new HtmlCommand(
          '.result_message',
          '<div class="my_top_message">'.$search_results_items.'</div>')
        );
        
        $model_number_array = array(
              '23976' => 'Masoneilan 74000 Series Case Study (English)',
            );
        
        //print '<pre>'; print_r($search_results_items); print '<pre>';
      $form['search_result']['#options'] = $search_results_items;
      return $form['search_result'];
      //return $response;
      //return ['#markup' => $response];

    
   }
   
   

  /**
  * Getter method for Form ID.
  *
  * The form ID is used in implementations of hook_form_alter() to allow other
  * modules to alter the render array built by this form controller.  it must
  * be unique site wide. It normally starts with the providing module's name.
  *
  * @return string
  *   The unique ID of the form defined by this class.
  */
  public function getFormId() {
    return 'bhge_digital_binder_simple_form';
  }

  /**
  * Implements form validation.
  *
  * The validateForm method is the default method called to validate input on
  * a form.
  *
  * @param array $form
  *   The render array of the currently built form.
  * @param FormStateInterface $form_state
  *   Object describing the current state of the form.
  */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    //$document_title = $form_state->getValue('document_title');
    //if (strlen($document_title) < 5) {
      // Set an error for the form element with a key of "title".
      //$form_state->setErrorByName('document_title', $this->t('The title must be at least 5 characters long.'));
      //exit();
    //}
    $search_result = $form_state->getValue('search_result'); //print '<br>';
    
    // Store data in temp variable
    // For "mymodule_name," any unique namespace will do. https://atendesigngroup.com/blog/storing-session-data-drupal-8
// I'd probably use "mymodule_name" most of the time.

    
    if(count($search_result)>0) {
       print '<pre>'; print_r($search_result); print '</pre>';
       
       $tempstore = \Drupal::service('user.private_tempstore')->get('bhge_digital_binder');
       $tempstore->set('search_result_data', $search_result);

       $tempstore = \Drupal::service('user.private_tempstore')->get('bhge_digital_binder');
       $some_data = $tempstore->get('search_result_data');
       
       print '<pre>'; print_r($some_data); print '</pre>';

       //print count($search_result);
       //return new RedirectResponse(\Drupal\Core\Url::fromRoute('reorder_docs'));
        //global $base_url; //set base path
        //$response = new Symfony\Component\HttpFoundation\RedirectResponse($base_url ."/xyz"); //set url
        $response = new RedirectResponse('/digital-binder-list' );
        $response->send();
        //$response->send();
       exit();
    }
  }

  /**
  * Implements a form submit handler.
  *
  * The submitForm method is the default method called for any submit elements.
  *
  * @param array $form
  *   The render array of the currently built form.
  * @param FormStateInterface $form_state
  *   Object describing the current state of the form.
  */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /*
    * This would normally be replaced by code that actually does something
    * with the title.
        */
    $search_result = $form_state->getValue('search_result'); //print '<br>';
    
    if(count($search_result)>0) {
       print '<pre>'; print_r($search_result); print '</pre>';
       print count($search_result);
       exit();
    }
    
    print $document_title = $form_state->getValue('document_title'); print '<br>';
    
    print $model_number = $form_state->getValue('model_number'); print '<br>';
    
    $brands = $form_state->getValue('brands'); print '<br>';
    print_r($brands);
    $brands_filter = array();
    foreach($brands as $key => $value) {
        if($key == $value) {
          array_push($brands_filter ,$value);
        }
    }
    
    $product_types_array = $form_state->getValue('product_types'); print '<br>';
    print_r($product_types_array);
    $products_filter = array();
    foreach($product_types_array as $key => $value) {
        if($key == $value) {
          array_push($products_filter ,$value);
        }
    }
    
    $file_types = $form_state->getValue('file_types'); print '<br>';
    print_r($file_types);
    $files_filter = array();
    foreach($file_types as $key => $value) {
        if($key == $value) {
          array_push($files_filter ,$value);
        }
    }
    
    $model_number = $form_state->getValue('model_number');
    $document_title = $form_state->getValue('document_title');
    $select_brand_filter = $brands_filter; 
    print_r($select_brand_filter);
    $field_select_product_filter = $products_filter;
    print_r($field_select_product_filter);
    $field_topic_filter = $files_filter;
    print_r($field_topic_filter);
    $field_part_number_filter = $model_number;
        
        $document_query = \Drupal::entityQuery('node');
        $document_query->condition('status', 1); //published or not
        $document_query->condition('type', 'document'); //content types
        $document_query->condition('field_language', '451'); // Only English docs required
        
            
        if(!empty($document_title)) {
           $document_query->condition('title', $document_title); //content title
        }
        if(!empty($select_brand_filter)) {
          $document_query->condition('select_brand', $select_brand_filter, 'IN'); // filtering brands
        }
        if(!empty($field_select_product_filter)) {
          $document_query->condition('field_select_product', $field_select_product_filter, 'IN'); // filtering product types
        }
        if(!empty($field_topic_filter)) {
          $document_query->condition('field_topic', $field_topic_filter, 'IN'); // filtering file types
        }
        if(!empty($field_part_number_filter)) {
          $document_query->condition('field_part_number', $field_part_number_filter, 'IN'); // filtering file types
        }
        
        $document_query->sort('created' , 'DESC');
        $document_query->pager(50); //specify results to return    
        $document_nids = $document_query->execute();

        foreach ($document_nids as $document_nid) {
          $document_node = \Drupal\node\Entity\Node::load($document_nid);
          
          print $document_title = $document_node->title->value; print '<br>';
          
          print "File Type/Topic : ".$field_topic = $document_node->get('field_topic')->getValue(); print '<br>';
          print '<pre>'; print_r($field_topic); print '<pre>'; //exit();
          
          print "Product Types : ".$field_select_product = $document_node->get('field_select_product')->getValue(); print '<br>';
          print '<pre>'; print_r($field_select_product); print '<pre>'; //exit();
          
          print "Brands : ".$select_brand = $document_node->get('select_brand')->getValue(); print '<br>';
          print '<pre>'; print_r($select_brand); print '<pre>';
          
          print "Field Part Number : ".$field_part_number = $document_node->get('field_part_number')->getValue(); print '<br>';
          print '<pre>'; print_r($field_part_number); print '<pre>';
          
          print '----------------';
          print '<br>';
        }
    
    
    exit();
        
        
  }

}

/*function getfileurl($dam_field_file_target_id) {
       $media = Media::load($dam_field_file_target_id);
          $media_field_asset = $media->get('field_asset')->getValue();
          $file = File::load($media_field_asset[0]['target_id']);
          $dam_file_uri = $file->getFileUri();
          $dam_url = file_create_url($dam_file_uri);
          return $dam_url;
   }*/