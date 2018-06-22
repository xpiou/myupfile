<?php
if($_GET['action']=='exists'){
	if(file_exists($_GET['filepath'].'/'.$_GET['fileName'])){
		echo 'exists';
		//exit();
	}
	exit();
}
class Upload{
    private $filepath; //�ϴ�Ŀ¼
    private $tmpPath;  //PHP�ļ���ʱĿ¼
    private $blobNum; //�ڼ����ļ���
    private $totalBlobNum; //�ļ�������
    private $fileName; //�ļ���

    public function __construct($tmpPath,$blobNum,$totalBlobNum,$fileName,$uppath){
        $this->tmpPath =  $tmpPath;
        $this->blobNum =  $blobNum;
        $this->totalBlobNum =  $totalBlobNum;
        $this->fileName =  $fileName;
        $this->filepath =  $uppath;
        
        $this->moveFile();
        $this->fileMerge();
    }
    
    //�ж��Ƿ������һ�飬�����������ļ��ϳɲ���ɾ���ļ���
    private function fileMerge(){
        if($this->blobNum == $this->totalBlobNum){
            $blob = '';
            for($i=1; $i<= $this->totalBlobNum; $i++){
                $blob .= file_get_contents($this->filepath.'/'. $this->fileName.'__'.$i);
            }
            file_put_contents($this->filepath.'/'. $this->fileName,$blob);
           $this->deleteFileBlob();
        }
    }
    
   //ɾ���ļ���
    private function deleteFileBlob(){
        for($i=1; $i<= $this->totalBlobNum; $i++){
            @unlink($this->filepath.'/'. $this->fileName.'__'.$i);
        }
    }
    
    //�ƶ��ļ�
    private function moveFile(){
	/*
		if(file_exists($this->filepath.'/'. $this->fileName)){
			$data['code'] = -1;
			$data['msg'] = 'file_exists'.$this->blobNum.'_';
			$data['file_path'] = '';
			header('Content-type: application/json');
			echo json_encode($data);
			//exit();
		
		}
		*/
        $this->touchDir();
        $filename = $this->filepath.'/'. $this->fileName.'__'.$this->blobNum;
        move_uploaded_file($this->tmpPath,$filename);
    }
    
    //API��������
    public function apiReturn(){
        if($this->blobNum == $this->totalBlobNum){
                if(file_exists($this->filepath.'/'. $this->fileName)){
                    $data['code'] = 2;
                    $data['msg'] = 'success';
                    $data['file_path'] = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['DOCUMENT_URI']).str_replace('.','',$this->filepath).'/'. $this->fileName;
                }
        }else{
                if(file_exists($this->filepath.'/'. $this->fileName.'__'.$this->blobNum)){
                    $data['code'] = 1;
                    $data['msg'] = 'waiting for all';
                    $data['file_path'] = '';
                }
        }
        header('Content-type: application/json');
        echo json_encode($data);
    }
    
    //�����ϴ��ļ���
    private function touchDir(){
        if(!file_exists($this->filepath)){
            return mkdir($this->filepath);
        }
    }
}

//ʵ��������ȡϵͳ��������
$upload = new Upload($_FILES['file']['tmp_name'],$_POST['blob_num'],$_POST['total_blob_num'],$_POST['file_name'],$_POST['uppath']);
//���÷��������ؽ��
$upload->apiReturn();