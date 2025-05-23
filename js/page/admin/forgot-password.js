import { forgotPassword} from "../account_model.js";
import {  SUCCESS,ACCOUNT_NOT_EXIST,  EMAIL_FORMAT_ERROR, SERVER_INTERNAL_ERROR } from "./constant.js";

const forgot = document.getElementById('forgot')
const forgotForm = document.getElementById('forgot-form')
const email = document.getElementById('email')
const emailError = document.getElementById('email-error')
const loading = document.getElementById("loading-mask");
const alertLB = document.getElementById("alert-mask");
const alertMessage = document.getElementById("alert-message");
const alertBtn = document.getElementById("submit-alert");
const confirmLB = document.getElementById("confirm-mask");


email.addEventListener('change',checkEmail);



forgotForm.addEventListener("submit", async function (e) {
  e.preventDefault();
 checkEmail()
  if(!email.value)return;
  try {
    const param = {
      email: email.value,
      manage: "index",
      task: "forgot-password",
    };
    loading.style.display = 'block';
    const response = await forgotPassword(param);
    loading.style.display = 'none';
    console.log(response);
     if (response.data.errCode === SUCCESS) {
          forgot.removeChild(forgotForm)
          const message = document.createElement('h3')
          message.innerText = '請查看收件匣中是否有主旨為「Languiverse validation code」的電子郵件'
          message.id = 'email-remind'
          forgot.appendChild(message)
          console.log(response);
        } else {
            switch(response.data.errCode){
                case EMAIL_FORMAT_ERROR :
                        alertMessage.innerText = "Email格式錯誤";
                    break;
                case ACCOUNT_NOT_EXIST :
                        alertMessage.innerText = "帳號不存在";
                        break;
                case SERVER_INTERNAL_ERROR :
                        alertMessage.innerText = response.data.errMsg;
                        break;
            }
     
          alertBtn.onclick = function () {
            alertLB.style.display = "none";
          };
          alertLB.style.display = "block";
       
        }
  } catch (error) {
    console.log(error)
  }
});




function checkEmail() {
  if (!email.value) {
    emailError.style.display = "inline";
    return;
  }
  emailError.style.display = "none";
}

