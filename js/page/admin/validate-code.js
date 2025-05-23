import {  validateResetCode } from "../account_model.js";
import { SUCCESS, CAPTCHA_ERROR, LINK_EXPIRED, SERVER_INTERNAL_ERROR } from "./constant.js";

const validateForm = document.getElementById('validate-form')
const validateCode = document.getElementById('validate-code')
const validateCodeError = document.getElementById('validate-code-error')
const query = new URLSearchParams(location.search)
const validateCodeHash = query.get('i')
const loading = document.getElementById("loading-mask");
const alertLB = document.getElementById("alert-mask");
const alertMessage = document.getElementById("alert-message");
const alertBtn = document.getElementById("submit-alert");
const confirmLB = document.getElementById("confirm-mask");


validateCode.addEventListener('change',checkValidateCode);




validateForm.addEventListener("submit", async function (e) {
  e.preventDefault();
  checkValidateCode()
  if(!validateCode.value)return;
  try {
    const param = {
      validateCode: validateCode.value,
      validateCodeHash:validateCodeHash,
      manage: "index",
      task: "validate-code",
    };
    loading.style.display = 'block';
    const response = await validateResetCode(param);
    loading.style.display = 'none';
    console.log(response);
     if (response.data.errCode === SUCCESS) {
        location.href = response.data.redirect 
          console.log(response);
        } else {
            switch(response.data.errCode){
                case CAPTCHA_ERROR :
                        alertMessage.innerText = "驗證碼錯誤";
                    break;
                case LINK_EXPIRED :
                        alertMessage.innerText = "連結已過期";
                        break;
                case SERVER_INTERNAL_ERROR :
     alertMessage.innerText = "系統錯誤，請重新申請";
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




function checkValidateCode() {
  if (!validateCode.value) {
    validateCodeError.style.display = "inline";
    return;
  }
  validateCodeError.style.display = "none";
}

