import { resetPassword} from "../account_model.js";
import {  SUCCESS,ACCOUNT_NOT_EXIST,  SERVER_INTERNAL_ERROR } from "./constant.js";

const resetForm = document.getElementById('reset-form')
const newPassword = document.getElementById('new-password')
const newPasswordError = document.getElementById('new-password-error')
const confirmPassword = document.getElementById('confirm-password')
const confirmPasswordError = document.getElementById('confirm-password-error')
const loading = document.getElementById("loading-mask");
const alertLB = document.getElementById("alert-mask");
const alertMessage = document.getElementById("alert-message");
const alertBtn = document.getElementById("submit-alert");
const confirmLB = document.getElementById("confirm-mask");


newPassword.addEventListener('change',checkNewPassword);
confirmPassword.addEventListener('change',checkConfirmPassword);



resetForm.addEventListener("submit", async function (e) {
  e.preventDefault();
 checkNewPassword()
 checkConfirmPassword()
  if(!newPassword.value||!confirmPassword.value||newPassword.value!=confirmPassword.value)return;
  try {
    const param = {
      id:document.getElementById('userId').value,
      password: newPassword.value,
      manage: "index",
      task: "reset-password",
    };
    loading.style.display = 'block';
    const response = await resetPassword(param);
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
            
                case ACCOUNT_NOT_EXIST :
                        alertMessage.innerText = "帳號不存在";
                        break;
                case SERVER_INTERNAL_ERROR :
     alertMessage.innerText = "系統錯誤";
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




function checkNewPassword() {
  if (!newPassword.value) {
    newPasswordError.style.display = "inline";
    return;
  }
  newPasswordError.style.display = "none";
}

function checkConfirmPassword() {
  if (!confirmPassword.value||newPassword.value!=confirmPassword.value) {
    confirmPasswordError.style.display = "inline";
    return;
  }
  confirmPasswordError.style.display = "none";
}
