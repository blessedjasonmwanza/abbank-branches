<?php
try {
    include '../config/medoo2.1.php';
    include '../config/db.php';
    if(isset($_POST['keyword'])){
        $keyword = $_POST['keyword'];
    }else{
        exit('Kindly provide a search term. ');
    }
} catch (\Throwable $th) {
    exit('sorry, something went wrong');
}
    $no_branches = true;
    $db->select('branches', '*', [
            "OR" => [
                "branch_name[~]" => "%$keyword%",
                "city[~]" => "%$keyword%",
                "area[~]" => "%$keyword%",
                "full_address[~]" => "%$keyword%",
            ]
        ], function(&$results){
        global $no_branches;
        $no_branches = false;
        $branch_id = $results['id'];
        $branch_name = $results['branch_name'];
        $city = $results['city'];
        $google_map_link = $results['google_map_link'];
        $contacts = explode(",", $results['contact_numbers']);
        echo '
        <div full-width branch-card class="branch animate__animated animate__zoomIn animate__slower">
            <div class="branch_profile_logo" style="background-image: url(\'img/AB-Bank-Favicon.png\');"></div>
            <div class="branch_profile_info">';
                if(count($contacts) > 0){
                    echo '
                    <span class="blue darken-4 white-text branch-call-icon" target="contacts_b'.$branch_id.'" c-pointer>
                        <i class="fa fa-phone" title="call branch"></i>
                    </span>
                    <span class="branch-contacts" id="contacts_b'.$branch_id.'">';
                    foreach ($contacts as $key => &$value) {
                        echo '
                            <a href="tel:'.str_replace(' ', '', $value).'">'.$value.'</a>
                        ';
                    }
                    echo '</span>';
                }
                echo '<div class="branch_name">'.$branch_name.'
                    <span style="display:block;clear:top;">
                        <i class="fa fa-building grey-text" style="font-size:smaller;"></i>
                        <i class="fa fa-map-marker-alt" style="font-size:smaller;"></i>
                        <div class="branch_city">'.$city.'</div>
                    </span>
                </div>
                <div style="margin-top: 3px" class="branch_hours">Mon - Fri <small><b>08 : 00hrs</b> - <b>14 : 00hrs</b> </small> Weekends <small><b>08 : 00hrs</b> - <b>14 : 00hrs</b> </small>
                    <div style="margin-top: 3px; margin-bottom: 3px; color: #ccc; font-weight: bold;">
                        <a href="'.$google_map_link.'" style="color: #47525dcc;" target="_blank">Get directions</a>
                    </div>
                </div>
            </div>
        </div>
        ';
    });
    if($no_branches){
        echo '<span error> Sorry, we could not locate any branch marching your keyword</span>';
    }
?>