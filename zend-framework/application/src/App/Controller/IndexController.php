<?php

class App_IndexController extends Zend_Controller_Action
{
    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {

    }

    public function shopAction()
    {
        $list = [

        ];

        $this->view->list = $list;
    }

    public function itemAction()
    {
        //Zend_Form
        if (isset($_POST) && strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            $keywords = strip_tags($_POST['search_term']);
        } else {
            $keywords = '';
        }

        $this->view->content = '';

        if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            $flickr = new Zend_Service_Flickr('381e601d332ab5ce9c25939570cb5c4b');

            try {
                $results = $flickr->tagSearch($keywords, array('per_page' => 50, 'tag_mode' => 'all'));

                if ($results->totalResults() > 0) {
                    $images = array();
                    foreach ($results as $result) {
                        if (isset($result->Medium)) {
                            $images[] = imagecreatefromjpeg($result->Medium->uri);
                            $heights[] = $result->Medium->height;
                            $widths[] = $result->Medium->width;
                        }
                    }
                    if (sizeof($images) == 0) {
                        $this->view->content .= '<p style="color: orange; font-weight: bold">No Results Found.</p>';
                    } else {
                        sort($heights);
                        sort($widths);
                        $max_height = array_pop($heights);
                        $max_width = array_pop($widths);
                        $output = realpath("./temp/") . DIRECTORY_SEPARATOR . mt_rand() . ".jpg";
                        foreach ($images as $key => $image) {
                            if (!file_exists('./temp')) {
                                mkdir("./temp");
                            }

                            $tmp = tempnam(realpath('./temp'), 'zflickr');
                            imagejpeg($image, $tmp);

                            chmod($tmp, 0777);

                            if (file_exists($output)) {
                                passthru("composite -dissolve 20 $tmp $output $output");
                                chmod($output, 0777);
                            } elseif (!isset($previous_image)) {
                                $previous_image = "$tmp";
                            } else {
                                passthru("composite -dissolve 20 $tmp $previous_image $output");
                                chmod($output, 0777);
                            }
                            $image_files[] = $tmp;
                        }
                        foreach ($image_files as $filename) {
                            unlink($filename);
                        }
                        //copy($output, basename($output));
                        //unlink($output);
                        $size = getimagesize($output);
                        $size[0] += 25;
                        $size[1] += 25;
                        $this->view->content .= "<div id='composite' style='width: {$size[0]}px; height: {$size[1]}px;'><img src='temp/" . basename($output) . "' alt='" . htmlspecialchars($keywords) . "'><h2>" . ucwords(htmlspecialchars($keywords)) . "</h2></div>";
                    }
                } else {
                    $this->view->content .= '<p style="color: orange; font-weight: bold">No Results Found</p>';
                }
            } catch (Zend_Service_Exception $e) {
                $this->view->content .= '<p style="color: red; font-weight: bold">An error occured, please try again later. (' . $e->getMessage() . ')</p>';
            }
        }
    }

    public function cartAction()
    {

    }
}

