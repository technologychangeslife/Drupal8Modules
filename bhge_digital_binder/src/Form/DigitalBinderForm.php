<?php
/**
 * @file
 * Contains \Drupal\bhge_digital_binder\Form\DigitalBinderForm.
 */
namespace Drupal\bhge_digital_binder\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;


class DigitalBinderForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'binder_form';
  }
  
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['document_title'] = array(
      '#type' => 'textfield',
      '#title' => t('Search with document title'),
      '#required' => TRUE,
    );
    
    $form['model_number'] = array(
      '#type' => 'textfield',
      '#title' => t('Search with Model Number'),
      '#required' => TRUE,
    );
    
    $query = \Drupal::entityQuery('node')
        ->condition('status', 1) //published or not
        ->condition('type', 'section') //content type
        ->pager(100); //specify results to return
        $nids = $query->execute();
        
        //$product_types_array = [];
        
        
        //print "Brands"; print '<br>';
        foreach ($nids as $nid) {
          $node = \Drupal\node\Entity\Node::load($nid); 
          $body = $node->body->value;
          if($node->title->value == 'Masoneilan' || $node->title->value == 'Consolidated' || $node->title->value == 'Mooney' || $node->title->value == 'Becker') {
            $nid;
            $title = $node->title->value; //print '<br>';
            $brands[$nid] = $title;
          }
        }

    
        # the options to display in our checkboxes
        /*$brands = array(
          '551' => t('Consolidated'),
          '561' => t('Masoneilan'), 
          '556' => t('Mooney'),
          '1186' => t('Becker'),
        );*/


        # the drupal checkboxes form field definition
        $form['brands'] = array(
          '#title' => t('Select Brands'),
          '#type' => 'checkboxes',
          '#description' => t('Select the brands you want.'),
          '#options' => $brands,
        );

  
        foreach ($nids as $nid) {
          $node = \Drupal\node\Entity\Node::load($nid); 
          $body = $node->body->value;
          if($node->title->value != 'Masoneilan' || $node->title->value != 'Consolidated' || $node->title->value != 'Mooney' || $node->title->value != 'Becker') {
            $nid;
            $title = $node->title->value; print '<br>';
            
            $product_types_array[$nid] = $title;
          }
        }

              /*$product_types_array = array(
              '376' => t('Regulators'),
              '361' => t('Safety Relief Valves'), 
              '331' => t('Software Tools'),
              '346' => t('Level Transmitters/Controllers'),
            );*/

        # the drupal checkboxes form field definition
        $form['product_types'] = array(
        '#title' => t('Select Product Types'),
        '#type' => 'checkboxes',
        '#description' => t('Select the product types you want.'),
        '#options' => $product_types_array,
        );
        
        
        $vid = 'documents_topic';
$terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
foreach ($terms as $term) {

 
 $term_data[$term->tid] = $term->name;
 
}
    # the drupal checkboxes form field definition
        $form['file_types'] = array(
        '#title' => t('Select File Types'),
        '#type' => 'checkboxes',
        '#description' => t('Select the file types you want.'),
        '#options' => $term_data,
        );
    
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    );
    return $form;
  }
  
  
  /*
  public function validateForm(array &$form, FormStateInterface $form_state) {
      
    }*/
    
    
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
   // drupal_set_message($this->t('@can_name ,Your application is being submitted!', array('@can_name' => $form_state->getValue('candidate_name'))));
    foreach ($form_state->getValues() as $key => $value) {
      print $key . ': ' . $value; print '<br>';
    }
    
    exit();
    
        $select_brand_filter = [556, 1186];
        $field_select_product_filter = [376];
        $field_topic_filter = [];
        $field_part_number_filter = [];
        
        if(!empty($select_brand_filter) && empty($field_select_product_filter) && empty($field_topic_filter) && empty($field_part_number_filter)) {
            $document_query = \Drupal::entityQuery('node')
            ->condition('status', 1) //published or not
            ->condition('type', 'document') //content type
            ->condition('select_brand', $select_brand_filter, 'IN') // filtering brands
            ->sort('created' , 'DESC')
            ->pager(50); //specify results to return
        }
        
        if(!empty($select_brand_filter) && !empty($field_select_product_filter) && empty($field_topic_filter) && empty($field_part_number_filter)) {
            $document_query = \Drupal::entityQuery('node')
            ->condition('status', 1) //published or not
            ->condition('type', 'document') //content type
            ->condition('select_brand', $select_brand_filter, 'IN') // filtering brands
            ->condition('field_select_product', $field_select_product_filter, 'IN') // filtering product types
            ->sort('created' , 'DESC')
            ->pager(50); //specify results to return
        }

        if(!empty($select_brand_filter) && !empty($field_select_product_filter) && !empty($field_topic_filter) && empty($field_part_number_filter)) {
            $document_query = \Drupal::entityQuery('node')
            ->condition('status', 1) //published or not
            ->condition('type', 'document') //content type
            ->condition('select_brand', $select_brand_filter, 'IN') // filtering brands
            ->condition('field_select_product', $field_select_product_filter, 'IN') // filtering product types
            ->condition('field_topic', $field_topic_filter, 'IN') // filtering file types
            ->sort('created' , 'DESC')
            ->pager(50); //specify results to return
        }
        
        if(!empty($select_brand_filter) && empty($field_select_product_filter) && empty($field_topic_filter) && !empty($field_part_number_filter)) {
            $document_query = \Drupal::entityQuery('node')
            ->condition('status', 1) //published or not
            ->condition('type', 'document') //content type
            ->condition('select_brand', $select_brand_filter, 'IN') // filtering brands
            ->condition('field_part_number', $field_part_number_filter, 'IN') // filtering file types
            ->sort('created' , 'DESC')
            ->pager(50); //specify results to return
        }
        
        if(!empty($select_brand_filter) && empty($field_select_product_filter) && !empty($field_topic_filter) && empty($field_part_number_filter)) {
            $document_query = \Drupal::entityQuery('node')
            ->condition('status', 1) //published or not
            ->condition('type', 'document') //content type
            ->condition('select_brand', $select_brand_filter, 'IN') // filtering brands
            ->condition('field_topic', $field_topic_filter, 'IN') // filtering file types
            ->sort('created' , 'DESC')
            ->pager(50); //specify results to return
        }
        
        if(empty($select_brand_filter) && !empty($field_select_product_filter) && !empty($field_topic_filter) && empty($field_part_number_filter)) {
            $document_query = \Drupal::entityQuery('node')
            ->condition('status', 1) //published or not
            ->condition('type', 'document') //content type
            ->condition('field_select_product', $field_select_product_filter, 'IN') // filtering product types
            ->condition('field_topic', $field_topic_filter, 'IN') // filtering file types
            ->sort('created' , 'DESC')
            ->pager(50); //specify results to return
        }
        
        if(empty($select_brand_filter) && !empty($field_select_product_filter) && empty($field_topic_filter) && !empty($field_part_number_filter)) {
            $document_query = \Drupal::entityQuery('node')
            ->condition('status', 1) //published or not
            ->condition('type', 'document') //content type
            ->condition('field_select_product', $field_select_product_filter, 'IN') // filtering product types
            ->condition('field_part_number', $field_part_number_filter, 'IN') // filtering file types
            ->sort('created' , 'DESC')
            ->pager(50); //specify results to return
        }
        
        if(empty($select_brand_filter) && empty($field_select_product_filter) && !empty($field_topic_filter) && !empty($field_part_number_filter)) {
            $document_query = \Drupal::entityQuery('node')
            ->condition('status', 1) //published or not
            ->condition('type', 'document') //content type
            ->condition('field_topic', $field_topic_filter, 'IN') // filtering file types
            ->condition('field_part_number', $field_part_number_filter, 'IN') // filtering file types
            ->sort('created' , 'DESC')
            ->pager(50); //specify results to return
        }
        
        if(!empty($select_brand_filter) && !empty($field_select_product_filter) && !empty($field_topic_filter) && !empty($field_part_number_filter)) {
            $document_query = \Drupal::entityQuery('node')
            ->condition('status', 1) //published or not
            ->condition('type', 'document') //content type
            ->condition('select_brand', $select_brand_filter, 'IN') // filtering brands
            ->condition('field_select_product', $field_select_product_filter, 'IN') // filtering product types
            ->condition('field_topic', $field_topic_filter, 'IN') // filtering file types
            ->condition('field_part_number', $field_part_number_filter, 'IN') // filtering file types
            ->sort('created' , 'DESC')
            ->pager(50); //specify results to return
        }
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