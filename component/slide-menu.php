<?php
$manage = $path_array[3];//從head.php取得path_array變數
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
  <h4>Hi!<?= htmlspecialchars($account->data['account']['name'])?></h4>
  <a class="<?= htmlspecialchars($manage) == 'account' ? 'active' : ''; ?> " href="<?= ROOT . '/page/admin/account/list.php' ?>">帳號管理</a>
  <a id="joke-trigger" class="block-trigger">冷笑話管理<span id="joke-icon" class="rotate-icon <?=htmlspecialchars($manage) == 'joke'||htmlspecialchars($manage) == 'joke_category'||htmlspecialchars($manage) == 'joke_tag'?'open':''?>">▲</span></a>
  <section id="joke-block" class="link-block <?=htmlspecialchars($manage) == 'joke'||htmlspecialchars($manage) == 'joke_category'||htmlspecialchars($manage) == 'joke_tag'?'open':''?>">
 <a class="<?= htmlspecialchars($manage) == 'joke' ? 'active' : ''; ?> " href="<?= ROOT . '/page/admin/joke/list.php' ?>">冷笑話</a>
  <a class="<?= htmlspecialchars($manage) == 'joke_category' ? 'active' : ''; ?> " href="<?= ROOT . '/page/admin/joke_category/list.php' ?>">類別</a>
  <a class="<?= htmlspecialchars($manage) == 'joke_tag' ? 'active' : ''; ?> " href="<?= ROOT . '/page/admin/joke_tag/list.php' ?>">標籤</a>
  </section>
 <a id="curse-trigger" class="block-trigger">負面語言管理<span id="curse-icon" class="rotate-icon <?= htmlspecialchars($manage) == 'curse'||htmlspecialchars($manage) == 'curse_category'||htmlspecialchars($manage) == 'curse_tag'?'open':''?>">▲</span></a>
  <section id="curse-block" class="link-block <?=htmlspecialchars($manage) == 'curse'?'open':'' ?>">
 <a class="<?= htmlspecialchars($manage) == 'curse' ? 'active' : ''; ?> " href="<?= ROOT . '/page/admin/curse/list.php' ?>">負面語言管理</a>
  </section>
  <a id="touching-trigger" class="block-trigger">感人話語管理<span id="touching-icon" class="rotate-icon">▲</span></a>
  <section id="touching-block" class="link-block">
 <a class="<?= htmlspecialchars($manage) == 'touching' ? 'active' : ''; ?> " href="<?= ROOT . '/page/admin/touching/list.php' ?>">感人話語</a>
  <a class="<?= htmlspecialchars($manage) == 'touching_source' ? 'active' : ''; ?> " href="<?= ROOT . '/page/admin/touching_source/list.php' ?>">出處</a>
  </section>
  <a id="trends-trigger" class="block-trigger">流行語管理<span id="trends-icon" class="rotate-icon <?= htmlspecialchars($manage) == 'trends'||htmlspecialchars($manage) == 'trends_age'?'open':''?>">▲</span></a>
  <section id="trends-block"  class="link-block  <?=htmlspecialchars($manage) == 'trends'||htmlspecialchars($manage) == 'trends_age'?'open':'' ?>">
  <a class="<?= htmlspecialchars($manage) == 'trends' ? 'active' : ''; ?> " href="<?= ROOT . '/page/admin/trends/list.php' ?>">流行語管理</a>
  <a class="<?= htmlspecialchars($manage) == 'trends_age' ? 'active' : ''; ?> " href="<?= ROOT . '/page/admin/trends_age/list.php' ?>">流行語時代管理</a>
  </section>
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
  const trendsTrigger = document.getElementById('trends-trigger')
  const trendsIcon = document.getElementById('trends-icon')
  const trendsBlock = document.getElementById('trends-block')

  jokeTrigger.addEventListener('click',function(){

      curseIcon.classList.remove('open')
    curseBlock.classList.remove('open')
       touchingIcon.classList.remove('open')
    touchingBlock.classList.remove('open')
      trendsIcon.classList.remove('open')
    trendsBlock.classList.remove('open')
    jokeIcon.classList.toggle('open')
    jokeBlock.classList.toggle('open')
  })

 curseTrigger.addEventListener('click',function(){

      jokeIcon.classList.remove('open')
    jokeBlock.classList.remove('open')
       touchingIcon.classList.remove('open')
    touchingBlock.classList.remove('open')
      trendsIcon.classList.remove('open')
    trendsBlock.classList.remove('open')
   
    curseIcon.classList.toggle('open')
    curseBlock.classList.toggle('open')
  })

  touchingTrigger.addEventListener('click',function(){

      curseIcon.classList.remove('open')
    curseBlock.classList.remove('open')
       jokeIcon.classList.remove('open')
    jokeBlock.classList.remove('open')
      trendsIcon.classList.remove('open')
   trendsBlock.classList.remove('open')
    touchingIcon.classList.toggle('open')
    touchingBlock.classList.toggle('open')
  })

   trendsTrigger.addEventListener('click',function(){

      curseIcon.classList.remove('open')
    curseBlock.classList.remove('open')
       touchingIcon.classList.remove('open')
    touchingBlock.classList.remove('open')
      jokeIcon.classList.remove('open')
    jokeBlock.classList.remove('open')
   
    trendsIcon.classList.toggle('open')
    trendsBlock.classList.toggle('open')
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