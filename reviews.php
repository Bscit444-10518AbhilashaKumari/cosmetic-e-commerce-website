<div class="reviews-container">
    <div class="heading">
        <h1>user's review</h1>
    </div>

    <div class="box-container">
        <?php 
        $select_review = $conn->prepare("SELECT * FROM review WHERE product_id = ?");
        $select_review->execute([$get_id]);

        if($select_review->rowCount() > 0){
            while($fetch_review = $select_review->fetch(PDO::FETCH_ASSOC)){

                $select_users = $conn->prepare("SELECT * FROM users WHERE id = ?");
                $select_users->execute([$fetch_review['user_id']]);
                $fetch_user = $select_users->fetch(PDO::FETCH_ASSOC);
        ?>
        
        <div class="box" <?php if($fetch_review['user_id'] == $user_id){ echo 'style="order:-1;"'; } ?>>

            <div class="user">
                <?php if(!empty($fetch_user['image'])){ ?>
                    <img src="../uploaded_files/<?= $fetch_user['image'] ?>">
                <?php } else { ?>
                    <h3><?= substr($fetch_user['name'], 0, 1) ?></h3>
                <?php } ?>

                <div>
                    <p><?= $fetch_user['name'] ?></p>
                    <p><?= $fetch_review['date'] ?></p>
                </div>
            </div>

            <div class="ratings">
                <?php
                $rating = $fetch_review['rating'];
                $color = 'red';

                if($rating >= 4){
                    $color = 'var(--main-color)';
                } elseif($rating == 3){
                    $color = 'orange';
                }
                ?>
                <p style="background: <?= $color ?>;">
                    <i class="bx bxs-star">
                        <span><?= $rating ?></span>
                    </i>
                </p>
            </div>

            <div class="detail">
                <div class="box-detail">
                    <div>
                        <?php if(!empty($fetch_review['photo'])){ ?>
                            <img src="../uploaded_files/<?= $fetch_review['photo']; ?>">
                        <?php } ?>
                    </div>

                    <div>
                        <h3 class="title"><?= $fetch_review['title']; ?></h3>

                        <?php if($fetch_review['description'] != ''){ ?>
                            <p class="description"><?= $fetch_review['description']; ?></p>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>

        <?php 
            }
        } else {
            echo "<p>No reviews yet.</p>";
        }
        ?>
    </div>
</div>