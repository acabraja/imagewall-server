<?php

class Upload extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('form', 'url'));
    $this->output->set_content_type('application/json');
    $this->load->model('picture_model');
  }
  
  //@TODO description
  function index($xcoordinate = NULL, $ycoordinate = NULL)
  { 
    if ($xcoordinate == NULL || $ycoordinate == NULL)
    {
       $this->output->set_output(json_encode(array()));
    }
    else
    {
      $this->output->set_output(json_encode($this->picture_model->get_events($xcoordinate, $ycoordinate)));
    }
  }

  /**
   * Receives images from client
   * picture, picture_name, coordinates.
   */
  function uploadImage($event = NULL, $xcoordinate = NULL, $ycoordinate = NULL)
  { 
    $config['upload_path'] = 'static/images';
    var_dump($config['upload_path']);
    $config['allowed_types'] = 'gif|jpg|png';
    $this->load->library('upload', $config);

    if (!$this->upload->do_upload())
    {
      $error = array('error' => $this->upload->display_errors());
      $this->load->view('upload_form', $error);
    }
    else
    {
      $error = $this->upload->display_errors();
      $data = array('upload_data' => $this->upload->data(),
                    'event' => $event,
                     'coordinate' => array( 'x-coordinate' => $xcoordinate, 
                                            'y-coordinate' => $ycoordinate));
      var_dump($data);
      $this->load->view('upload_form', array('data' => $data,
                                             'error' => $error));
      $this->picture_model->upload_image($data);
    }
  }
}
?>
