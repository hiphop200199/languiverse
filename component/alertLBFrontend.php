<style>
    #alert-mask{
        position: fixed;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.7);
  width: 100dvw;
  height: 100dvh;
  z-index: 2;
  display: none;
    }
    #alert-mask #alert-lb{
        position: fixed;
  left: 50%;
  top: 50%;
  transform: translate(-50%,-50%);
  border-radius: 3px;
  box-shadow: 0 0 3px 2px #DCF2F1;
  border: 3px solid #365486;
  display: flex;
  flex-direction: column;
  justify-content: space-evenly;
  align-items: center;
  width: 300px;
  height: 350px;
  background-color: #fff;
    }
    #alert-mask #alert-lb #result{
           width: 120px;
  height: 120px;
    }
    #alert-mask #alert-lb #alert-btn{
         font-size: 20px;
  font-weight: 700;
  color: #fff;
  background-color: var(--c4);
  border-radius: 3px;
  padding: 0 3px;
  border: none;
  cursor: pointer;
    }
</style>
<div id="alert-mask" >
      <div id="alert-lb" >
        <img id="result" src="<?=ROOT.'/image/ok.gif'?>" alt="">
          <button id="alert-btn">確認</button>
       </div>
    </div>