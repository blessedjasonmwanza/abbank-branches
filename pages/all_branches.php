<?php
try {
    include '../config/medoo2.1.php';
    include '../config/db.php';
} catch (\Throwable $th) {
    exit('sorry, something went wrong');
}
?>
<span flex full-with center j-center class="card-panel darken-2 white-text" style="margin-top: 0px;" bg-blue>
    <h4 class="white-text" grey>Our Branches</h4>
</span>
<div full-width>
    <section branches-list style="margin: auto;">
        <form class="card-panel input-field" search_branch_form>
            <input type="search" placeholder="Search branch" search_term required>
            <span flex full-width space-between row wrap>
                <span></span>
                <button branch_search_btn class="btn btn-sm blue white-text"><i class="fa fa-search"></i></button>
            </span>
        </form> 
        <div id="branch_display_section">
            <?php
                $no_branches = true;
                $db->select('branches', '*', function(&$results){
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
                                    <a href="'.$google_map_link.'" style="color: #47525dcc;">Get directions</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    ';
                });
                if($no_branches){
                    echo 'Sorry, it seems like we have not yet listed our branches in this area.';
                }
            ?>
        </div>
    </section>
</div>
<script>
    $('.branch-call-icon').off().click(function(){
        let contactgs_elem = $(this).attr('target');
        $('#'+contactgs_elem+'').toggle();
    });

    $('[search_branch_form]').off().submit(function(e){
        e.preventDefault();
        let display_section = $('#branch_display_section');
        let keyword = $('[search_term]').val();
        $.post('search/branches.php', {"keyword": keyword}, function(response){
            display_section.html(response);
        }).fail((error) =>{
            console.warn(error.responseText);
            toast('<span>Something went wrong, please try again.</span>');
        });
    })
</script>