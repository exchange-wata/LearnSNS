            <div class="collapse" id="collapseComment<?php echo $feed["id"]; ?>">
                <div class="col-xs-12" style="margin-top:10px;">
                  
                  <!-- $feed["comment"]=コメント情報が配列の形で入ってる -->
                  <?php foreach ($feed["comments"] as $comment) { ?>
                  <img src="user_profile_img/<?php echo $comment['img_name']; ?>" width="40" class="img-circle">
                  <span style="border-radius: 100px!important; -webkit-appearance:none;background-color:#eff1f3;padding:10px;margin-top:10px;"><a href="profile.php?user_id=<?php echo $comment['user_id']; ?>"><?php echo $comment['name']; ?></a><?php echo $comment["comment"]; ?></span>
                  <br>
                  <?php } ?>
                  
                  <!-- <img src="https://placehold.jp/40x40" width="40" class="img-circle">
                  <span style="border-radius: 100px!important; -webkit-appearance:none;background-color:#eff1f3;padding:10px;margin-top:10px;"><a href="#">おもえもん</a> のびたくん。。。？</span>
                  <br>
                  
                  <img src="https://placehold.jp/40x40" width="40" class="img-circle">
                  <span style="border-radius: 100px!important; -webkit-appearance:none;background-color:#eff1f3;padding:10px;margin-top:10px;"><a href="#">おもえもん</a> のびたくん。。。？</span>
                  <br>
                   -->

                  <form method="post" class="form-inline" action="comment.php" role="comment">
                    <div class="form-group">
                      <img src="user_profile_img/<?php echo $signin_user['img_name']; ?>" width="40" class="img-circle">
                    </div>
                    <div class="form-group">
                        <input type="text" name="write_comment" class="form-control" style="width:400px;border-radius: 100px!important; -webkit-appearance:none;" placeholder="コメントを書く">
                    </div>
                    <input type="hidden" name="feed_id" value="<?php echo $feed["id"]; ?>">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Post</button>
                    </div>
                  </form>
                </div>
              </div>