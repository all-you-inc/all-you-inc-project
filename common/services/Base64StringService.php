<?php 

namespace common\services;

use Yii;

class Base64StringService {

    public function base64_to_file($base64_string) 
    {

        // Resource File
        $output_file = null;

        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $dataArr = explode( ',', $base64_string );

        // File Extension
        $file_extension = $this->getImageExtension(explode(';',explode(':', $dataArr[0])[1])[0]);

        // File Name
        $fileName = Yii::$app->security->generateRandomString(24);

        if($file_extension != 'Extension Not Found')
        {
            //File 
            $output_file = $fileName . $file_extension;
        }
        else
        {
            return 'Exception';
        }
         
        // actual base64 string
        $base64_string = $dataArr[1];

        // open the output file for writing
        $ifp = fopen( $output_file, 'wb' ); 
        
    
        // we could add validation here with ensuring count( $data ) > 1
        fwrite( $ifp, base64_decode($base64_string));
    
        // clean up the file resource
        fclose( $ifp ); 
    
        return $output_file; 
    }

    public function getImageExtension($mimeType)
    {
        $mimeArrayTypes = $this->getAllImageMimeType();

        foreach($mimeArrayTypes as $mime)
        {
            if($mime[0] == $mimeType)
            {
                return $mime[1];
            }
        }
        return 'Extension Not Found';
    }

    public function getAllImageMimeType()
    {
        $mimeArray = array();
        
        // image/bmp 
        $mimeArray[0][0] = 'image/bmp';
        $mimeArray[0][1] = 'bmp';
        
        // image/gif 
        $mimeArray[1][0] = 'image/gif';
        $mimeArray[1][1] = 'gif';
        
        // image/vnd.microsoft.icon
        $mimeArray[2][0] = 'image/vnd.microsoft.icon';
        $mimeArray[2][1] = 'ico';
        
        // image/jpeg 
        $mimeArray[3][0] = 'image/jpeg';
        $mimeArray[3][1] = 'jpg';
        
        // image/png 
        $mimeArray[4][0] = 'image/png';
        $mimeArray[4][1] = 'png';
        
        // image/svg+xml 
        $mimeArray[5][0] = 'image/svg+xml';
        $mimeArray[5][1] = 'svg';
        
        // image/tiff
        $mimeArray[6][0] = 'image/tiff';
        $mimeArray[6][1] = 'tif';
        
        // image/webp
        $mimeArray[7][0] = 'image/webp';
        $mimeArray[7][1] = 'webp';    

        return $mimeArray;
    }
}
