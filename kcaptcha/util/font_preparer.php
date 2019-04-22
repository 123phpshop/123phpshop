<?php 
/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015~2019 上海序程信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.123PHPSHOP.com；
 * ----------------------------------------------------------------------------
 * 这是一个免费的软件。您可以在商业目的和非商业目的地前提下对程序除本声明之外的
 * 代码进行修改和使用；您可以对程序代码进行再发布，但一定请保留
 * 本声明和上海序程信息科技有限公司的联系方式！本软件中使用到的第三方代码版权属
 * 于原公司所有。上海序程信息科技有限公司拥有对本声明和123PHPSHOP软件使用的最终
 * 解释权！
 * ============================================================================
 *  作者:	123PHPSHOP团队
 *  手机:	13391334121
 *  邮箱:	service@123phpshop.com
 */
 ?><?

# preparing PNG fonts to use with KCAPTCHA.
# reads files from folder "../fonts0", scans for symbols ans spaces and writes new font file with cached symbols positions to filder "../fonts"

# comment or remove next line for using (commented for security reason):
//exit();

if ($handle = opendir('../fonts0')) {
    while (false !== ($file = readdir($handle))) {
        if ($file == "." || $file == "..") {
        	continue;
        }

        $img=imagecreatefrompng('../fonts0/'.$file);
        imageAlphaBlending($img, false);
		imageSaveAlpha($img, true);
        $transparent=imagecolorallocatealpha($img,255,255,255,127);
        $white=imagecolorallocate($img,255,255,255);
        $black=imagecolorallocate($img,0,0,0);
        $gray=imagecolorallocate($img,100,100,100);

        for($x=0;$x<imagesx($img);$x++){
        	$space=true;
        	$column_opacity=0;
        	for($y=1;$y<imagesy($img);$y++){
        		$rgb = ImageColorAt($img, $x, $y);
        		$opacity=$rgb>>24;
        		if($opacity!=127){
        			$space=false;
        		}
        		$column_opacity+=127-$opacity;
        	}
        	if(!$space){
        		imageline($img,$x,0,$x,0,$column_opacity<200?$gray:$black);
        	}
        }
        imagepng($img,'../fonts/'.$file);
    }
    closedir($handle);
}
?>