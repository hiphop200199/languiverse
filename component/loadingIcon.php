<style>
    #loading-mask{
        position: fixed;
  inset: 0;
  display: none;
  background-color: rgba(0, 0, 0, 0.7);
  width: 100dvw;
  height: 100dvh;
  z-index: 2;
    }
    #loading-mask #loading{
        position: fixed;
        left: 50%;
        top: 50%;
        transform: translate(-50%,-50%);
        border-radius: 3px;
  box-shadow: 0 0 3px 2px #DCF2F1;
  border: 3px solid #365486;
    }
    #loading-mask #icon{
        width: 160px;
  height: 160px;
    }
</style>
<div id="loading-mask">
      <div id="loading">
        <img id="icon" src="<?=ROOT.'/image/galaxy.gif'?>" alt="">
       </div>
    </div>