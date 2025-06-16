<?php
$manage = $path_array[3];
require_once $_SERVER['DOCUMENT_ROOT'] . '/component/loading.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/account.php';
$account = new Account(new Account_model(new Db()));
?>
<link rel="stylesheet" href="<?php echo ROOT . '/css/page/admin/slide-menu.css' ?>">
<button id="hamburger-menu">☰</button>
<section id="side-menu">
  <div id="img-box">
    <h1 id="logo">言宇宙學院</h1>
  </div>
  <h4>Hi!<?=$account->data['account']['name']?></h4>
  <a class="<?= $manage == 'account' ? 'active' : ''; ?> " href="<?= ROOT . '/page/admin/account/list.php' ?>">帳號管理</a>
  <a id="joke-trigger" class="block-trigger">冷笑話管理<span id="joke-icon" class="rotate-icon <?=$manage == 'joke'||$manage == 'joke_category'||$manage == 'joke_tag'?'open':''?>">▲</span></a>
  <section id="joke-block" class="link-block <?=$manage == 'joke'||$manage == 'joke_category'||$manage == 'joke_tag'?'open':''?>">
 <a class="<?= $manage == 'joke' ? 'active' : ''; ?> " href="<?= ROOT . '/page/admin/joke/list.php' ?>">冷笑話管理</a>
  <a class="<?= $manage == 'joke_category' ? 'active' : ''; ?> " href="<?= ROOT . '/page/admin/joke_category/list.php' ?>">冷笑話類別管理</a>
  <a class="<?= $manage == 'joke_tag' ? 'active' : ''; ?> " href="<?= ROOT . '/page/admin/joke_tag/list.php' ?>">冷笑話標籤管理</a>
  </section>
 <!--  <a id="curse-trigger" class="block-trigger">黑語言管理<span id="curse-icon" class="rotate-icon <= $manage == 'curse'||$manage == 'curse_category'||$manage == 'curse_tag'?'open':''?>">▲</span></a>
  <section id="curse-block" class="link-block <=$manage == 'curse'||$manage == 'curse_category'||$manage == 'curse_tag'?'open':'' ?>">
 <a class="<= $manage == 'curse' ? 'active' : ''; ?> " href="<= ROOT . '/page/admin/curse/list.php' ?>">黑語言管理</a>
  <a class="<= $manage == 'curse_category' ? 'active' : ''; ?> " href="<= ROOT . '/page/admin/curse_category/list.php' ?>">黑語言類別管理</a>
  <a class="<= $manage == 'curse_tag' ? 'active' : ''; ?> " href="<= ROOT . '/page/admin/curse_tag/list.php' ?>">黑語言標籤管理</a>
  </section> -->
  <a id="touching-trigger" class="block-trigger">感人話語管理<span id="touching-icon" class="rotate-icon">▲</span></a>
  <section id="touching-block" class="link-block">
 <a class="<?= $manage == 'touching' ? 'active' : ''; ?> " href="<?= ROOT . '/page/admin/touching/list.php' ?>">感人話語管理</a>
  </section>
  <!-- <a id="flower-trigger" class="block-trigger">花語管理<span id="flower-icon" class="rotate-icon <= $manage == 'flower_meaning'||$manage == 'flower_meaning_category'?'open':''?>">▲</span></a>
  <section id="flower-block"  class="link-block  <=$manage == 'flower_meaning'||$manage == 'flower_meaning_category'?'open':'' ?>">
  <a class="<= $manage == 'flower_meaning' ? 'active' : ''; ?> " href="<= ROOT . '/page/admin/flower_meaning/list.php' ?>">花語管理</a>
  <a class="<= $manage == 'flower_meaning_category' ? 'active' : ''; ?> " href="<= ROOT . '/page/admin/flower_meaning_category/list.php' ?>">花語類別管理</a>
  </section> -->
  <a id="logout">登出</a>
</section>
<script>
  const hamburgerMenu = document.getElementById('hamburger-menu')
  const sideMenu = document.getElementById('side-menu')
  const logoutBtn = document.getElementById('logout')
  const loading = document.getElementById("loading-mask");
  const SUCCESS = 1;
  const jokeTrigger = document.getElementById('joke-trigger')
  const jokeIcon = document.getElementById('joke-icon')
  const jokeBlock = document.getElementById('joke-block')
  const curseTrigger = document.getElementById('curse-trigger')
  const curseIcon = document.getElementById('curse-icon')
  const curseBlock = document.getElementById('curse-block')
  const touchingTrigger = document.getElementById('touching-trigger')
  const touchingIcon = document.getElementById('touching-icon')
  const touchingBlock = document.getElementById('touching-block')
  const flowerTrigger = document.getElementById('flower-trigger')
  const flowerIcon = document.getElementById('flower-icon')
  const flowerBlock = document.getElementById('flower-block')

  jokeTrigger.addEventListener('click',function(){

      curseIcon.classList.remove('open')
    curseBlock.classList.remove('open')
       touchingIcon.classList.remove('open')
    touchingBlock.classList.remove('open')
      flowerIcon.classList.remove('open')
    flowerBlock.classList.remove('open')
   
    jokeIcon.classList.toggle('open')
    jokeBlock.classList.toggle('open')
  })

curseTrigger.addEventListener('click',function(){

      jokeIcon.classList.remove('open')
    jokeBlock.classList.remove('open')
       touchingIcon.classList.remove('open')
    touchingBlock.classList.remove('open')
      flowerIcon.classList.remove('open')
    flowerBlock.classList.remove('open')
   
    curseIcon.classList.toggle('open')
    curseBlock.classList.toggle('open')
  })

  touchingTrigger.addEventListener('click',function(){

      curseIcon.classList.remove('open')
    curseBlock.classList.remove('open')
       jokeIcon.classList.remove('open')
    jokeBlock.classList.remove('open')
      flowerIcon.classList.remove('open')
    flowerBlock.classList.remove('open')
   
    touchingIcon.classList.toggle('open')
    touchingBlock.classList.toggle('open')
  })

  flowerTrigger.addEventListener('click',function(){

      curseIcon.classList.remove('open')
    curseBlock.classList.remove('open')
       touchingIcon.classList.remove('open')
    touchingBlock.classList.remove('open')
      jokeIcon.classList.remove('open')
    jokeBlock.classList.remove('open')
   
    flowerIcon.classList.toggle('open')
    flowerBlock.classList.toggle('open')
  })

  hamburgerMenu.addEventListener('click', function() {
    this.classList.toggle('open')
    sideMenu.classList.toggle('open')
  })

  logoutBtn.addEventListener('click', async function() {

    try {
      const param = {
        manage: "index",
        task: "logout",
      };
     
      loading.style.display = "block";
      const response = await logout(param);
     
      console.log(response);
      if(response.data.errCode === SUCCESS){
        location.href = response.data.redirect
      }
    } catch (error) {
      console.log(error);
    }
  })

  async function logout(param){
     const response = await  axios.post('https://languiverse.kesug.com/controller/admin/index.php',param);
    return response;
  }
</script>