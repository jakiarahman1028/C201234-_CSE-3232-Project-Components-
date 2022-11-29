<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
$select_likes->execute([$user_id]);
$total_likes = $select_likes->rowCount();

$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
$select_comments->execute([$user_id]);
$total_comments = $select_comments->rowCount();

$select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
$select_bookmark->execute([$user_id]);
$total_bookmarked = $select_bookmark->rowCount();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>HOME</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- quick select section starts  -->

<section class="quick-select">

   <h1 class="heading">Quick options</h1>

   <div class="box-container">

      <?php
         if($user_id != ''){
      ?>
      <div class="box">
         <h3 class="title">Likes and comments</h3>
         <p>Total likes : <span><?= $total_likes; ?></span></p>
         <a href="likes.php" class="inline-btn">View likes</a>
         <p>Total comments : <span><?= $total_comments; ?></span></p>
         <a href="comments.php" class="inline-btn">View comments</a>
         <p>Saved playlist : <span><?= $total_bookmarked; ?></span></p>
         <a href="bookmark.php" class="inline-btn">View bookmark</a>
      </div>
      <?php
         }else{ 
      ?>
      <div class="box" style="text-align: center;">
         <h3 class="title">Please login or register</h3>
          <div class="flex-btn" style="padding-top: .5rem;">
            <a href="login.php" class="option-btn">Login</a>
            <a href="register.php" class="option-btn">Register</a>
         </div>
      </div>
      <?php
      }
      ?>

      <div class="box">
         <h3 class="title">Top categories</h3>
         <div class="flex">
            <a href="search_course.php?"><i class="fas fa-code"></i><span>Skill Development</span></a>
            <a href="#"><i class="fas fa-chart-simple"></i><span>Acadmic Courses</span></a>
            <a href="#"><i class="fas fa-pen"></i><span>Kids Learning</span></a>
            <a href="#"><i class="fa-solid fa-language"></i><span>Language Learning</span></a>
            <a href="#"><i class="fas fa-music"></i><span>Finance and Accounting</span></a>
            <a href="#"><i class="fas fa-camera"></i><span>Personal Development</span></a>
            <a href="#"><i class="fas fa-cog"></i><span>Design and Marketing</span></a>
            <a href="#"><i class="fas fa-vial"></i><span>Teaching and Academics</span></a>
         </div>
      </div>

      <div class="box">
         <h3 class="title">Popular topics</h3>
         <div class="flex">
            <a href="#"><i class="fab fa-html5"></i><span>Web Development</span></a>
            <a href="#"><i class="fa-brands fa-stack-overflow"></i><span>Software Engineering</span></a>
            <a href="#"><i class="fa-solid fa-language"></i><span>Spoken English</span></a>
            <a href="#"><i class="fa-brands fa-waze"></i><span>Pre-Schoolers</Pre-Schoolers></span></a>
            <a href="#"><i class="fa-brands fa-square-youtube"></i><span>Online Education</span></a>
            <a href="#"><i class="fa-solid fa-user-plus"></i><span>Human Resources</span></a>
         </div>
      </div>

      <div class="box tutor">
         <h3 class="title">Become a tutor</h3>
         <p>Teach what you know and help learners explore their interests, gain new skills, and advance their careers.</p>
         <a href="admin/register.php" class="inline-btn">get started</a>
      </div>

   </div>

</section>

<!-- quick select section ends -->

<!-- courses section starts  -->

<section class="courses">

   <h1 class="heading">Latest courses</h1>

   <div class="box-container">

      <?php
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? ORDER BY date DESC LIMIT 6");
         $select_courses->execute(['active']);
         if($select_courses->rowCount() > 0){
            while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
               $course_id = $fetch_course['id'];

               $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
               $select_tutor->execute([$fetch_course['tutor_id']]);
               $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box">
         <div class="tutor">
            <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_tutor['name']; ?></h3>
               <span><?= $fetch_course['date']; ?></span>
            </div>
         </div>
         <img src="uploaded_files/<?= $fetch_course['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fetch_course['title']; ?></h3>
         <a href="playlist.php?get_id=<?= $course_id; ?>" class="inline-btn">view playlist</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">No courses added yet!</p>';
      }
      ?>

   </div>

   <div class="more-btn">
      <a href="courses.php" class="inline-option-btn">view more</a>
   </div>

</section>

<!-- courses section ends -->












<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>