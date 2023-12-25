<?php
namespace SRC\Services\UI;

class UIFragments{

    public static function renderPageTitle( $title, $subTitle, $actionText, $action ) {
        echo '<div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="row">
                        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                            <h3 class="font-weight-bold">
                                '.$title.'
                            </h3>
                            <h6 class="font-weight-normal mb-0">
                                '.$subTitle.'
                            </h6>
                        </div>
                        <div class="col-12 col-xl-4">
                            <div class="justify-content-end d-flex">
                                <div class="flex-md-grow-1 flex-xl-grow-0">
                                    <a class="btn btn-md btn-outline-primary" type="button" id="dropdownMenuDate2" '.$action.'>
                                        <i class="mdi mdi-calendar"></i> '.$actionText.'
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
    }

    public static function renderPageTitleNoAction( $title, $subTitle ) {
        echo '<div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="row">
                        <div class="col-12 mb-4 mb-xl-0">
                            <h3 class="font-weight-bold">
                                '.$title.'
                            </h3>
                            <h6 class="font-weight-normal mb-0">
                                '.$subTitle.'
                            </h6>
                        </div>
                        
                    </div>
                </div>
            </div>';
    }

    public static function renderEmptyRecords( $title, $subTitle, $actionText, $action = false ) {
        $render  = '<div class="row">';			 
        $render .= '<div class="col-md-12">';
        $render .= '<div class="card">';
        $render .= '<div class="card-body cart">';
        $render .= '<div class="col-sm-12 empty-cart-cls text-center">';
        $render .= '<img src="https://i.imgur.com/dCdflKN.png" width="130" height="130" class="img-fluid mb-4 mr-3">';
        $render .= '<h3><strong>' . $title . '</strong></h3>';
        $render .= '<h4>' . $subTitle . '</h4>';
        if( $action ) {
            $render .= '<a ' . $action . ' class="btn btn-primary cart-btn-transform m-3" data-abc="true">' . $actionText . '</a>';
        }
        $render .= '</div>';
        $render .= '</div>';
        $render .= '</div>';
        $render .= '</div>';
        $render .= '</div>';

        echo $render;
    }
}

?>
