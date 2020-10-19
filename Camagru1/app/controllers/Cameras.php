<?php
    class Cameras extends Controller{
        public function __construct(){
            $this->cameraModel = $this->model('Camera');
            $this->postModel = $this->model('Post');
        }

        /// index Camera

        public function index(){
            $img = $this->cameraModel->get_img($_SESSION['user_id']);
                $data =[
                    'title' => 'Camera',
                    'user_id' => $_SESSION['user_id'],
                    'img' => $img,
                ];
                if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                    if (isset($_FILES['file']))
                        $name_file = $_FILES['file']['name'];
                        $tmp_name = $_FILES['file']['tmp_name'];
                        $upload_directory = APPROOT1; //This is the folder which you created just now
                        if (move_uploaded_file($tmp_name, $upload_directory.$name_file)){

                            $data1 = [
                                'img_dir' => base64_encode(file_get_contents($upload_directory.$name_file)),
                                'type' => getimagesize($upload_directory.$name_file),
                                'filter' => trim($_POST['filter'])
                            ];
                            $data = array_merge($data, $data1);
                            if ($this->postModel->addPost($data))
                                redirect('cameras');
                            else
                            $this->view('cameras/index', $data);
                        }
                        else{
                            $data1 = [
                                'img_dir' => trim($_POST['img64']),
                                'filter' => trim($_POST['filter'])
                            ];
                            $data = array_merge($data, $data1);
                            if ($this->postModel->addPost($data))
                                redirect('cameras');
                        }
                }else
                    $this->view('cameras/index', $data);
        }

        /// Delete preview

        public function delete_preview($id){
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                $this->postModel->deletePost($id);
                dlt_img_path();
                redirect('cameras');
            }
            redirect('cameras');
        }
        
        
    }