
                <ul class="list-group my-4">
                    <li class="list-group-item active">Users list</li>
                    <?php
                        foreach($display_users as $users){
                            if ($users['id'] !== $_SESSION['id']) {
                                if ($users['login_status'] == "online") {
                                    $status = "success";
                                }
                                else{
                                    $status = "danger";
                                }
                             echo ' <li class="list-group-item">
                             <img style="border-radius:50%;" src="../'.$users['profile'].'"class="mr-2" alt="profile picture" height="50px;" width="50px">'
                             .$users['username'].'<i style="font-size:10px;" class="fa ml-2 fa-circle text-'.$status.'"> </i>
                         </li>';
                            }
                        }
                    ?>
                   
                    
                </ul> 