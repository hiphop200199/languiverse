import { login } from "../account_model.js";
import { REQUEST_BASE_URL } from "./config.js";
import { SUCCESS,ACCOUNT_INACTIVE, ACCOUNT_OR_PASSWORD_ERROR, CAPTCHA_ERROR } from "./constant.js";

const loginForm = document.getElementById('login-form')
const account = document.getElementById('account')
const password = document.getElementById('password')
const captchaCode = document.getElementById('captcha-code')
const accountError = document.getElementById('account-error')
const passwordError = document.getElementById('password-error')
const captchaError = document.getElementById('captcha-error')
const captchaImage = document.getElementById('captcha-image')
const refresh = document.getElementById('refresh')
const loading = document.getElementById("loading-mask");
const alertLB = document.getElementById("alert-mask");
const alertMessage = document.getElementById("alert-message");
const alertBtn = document.getElementById("submit-alert");
const confirmLB = document.getElementById("confirm-mask");


account.addEventListener('change',checkAccount);
password.addEventListener('change',checkPassword);
captchaCode.addEventListener('change',checkCaptcha);


refresh.addEventListener('click',function (e) {
    e.preventDefault()
      captchaImage.src = ''
          captchaImage.src = REQUEST_BASE_URL + '/component/captcha.php'
})


loginForm.addEventListener("submit", async function (e) {
  e.preventDefault();
  checkAccount();
  checkPassword()
 checkCaptcha()
  if(!account.value||!password.value||!captchaCode.value)return;
  try {
    const param = {
      account: account.value,
      password:password.value,
      captcha:captchaCode.value,
      manage: "index",
      task: "login",
    };
    loading.style.display = 'block';
    const response = await login(param);
    loading.style.display = 'none';
    console.log(response);
     if (response.data.errCode === SUCCESS) {
          alertMessage.innerText = "成功";
          alertBtn.onclick = function () {
            location.href = response.data.redirect;
          };
          alertLB.style.display = "block";
          console.log(response);
        } else {
            switch(response.data.errCode){
                case CAPTCHA_ERROR :
                        alertMessage.innerText = "驗證碼錯誤";
                    break;
                case ACCOUNT_INACTIVE :
                        alertMessage.innerText = "帳號已停效";
                        break;
                case ACCOUNT_OR_PASSWORD_ERROR :
                        alertMessage.innerText = "帳號或密碼錯誤";
                        break;
                default :
     alertMessage.innerText = "系統錯誤";
     break;
            }
     
          alertBtn.onclick = function () {
            alertLB.style.display = "none";
          };
          alertLB.style.display = "block";
          captchaImage.src = ''
          captchaImage.src = REQUEST_BASE_URL + '/component/captcha.php'
        }
  } catch (error) {
    console.log(error)
  }
});




function checkAccount() {
  if (!account.value) {
    accountError.style.display = "inline";
    return;
  }
  accountError.style.display = "none";
}

function checkPassword() {
  if (!password.value) {
    passwordError.style.display = "inline";
    return;
  }
  passwordError.style.display = "none";
}



function checkCaptcha(){
  if (!captchaCode.value) {
    captchaError.style.display = "inline";
    return;
  }
  captchaError.style.display = "none";
}