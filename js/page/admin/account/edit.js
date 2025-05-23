import { IS_ACTIVE, IS_ADMIN, IS_NOT_ADMIN, SUCCESS } from "../constant.js";
import { edit,editPassword } from "../../account_model.js";

const form = document.getElementById("account-edit");
const account = document.getElementById("account");
const accountError = document.getElementById('account-error')
const passwordLB = document.getElementById('password-mask')
const openPassword = document.getElementById('password-btn')
const closePassword = document.getElementById('close-password')
const submitPassword = document.getElementById('submit-password')
const newPassword = document.getElementById("new-password");
const newPasswordError = document.getElementById('new-password-error')
const confirmPassword = document.getElementById('confirm-password')
const comfirmPasswordError = document.getElementById('confirm-password-error')
const nickname = document.getElementById("name");
const nicknameError = document.getElementById('nickname-error')
const email = document.getElementById('email')
const emailError = document.getElementById('email-error')
const cancel = document.getElementById("cancel");
const loading = document.getElementById("loading-mask");
const alertLB = document.getElementById("alert-mask");
const alertMessage = document.getElementById("alert-message");
const alertBtn = document.getElementById("submit-alert");
const confirmLB = document.getElementById("confirm-mask");
const query = new URLSearchParams(location.search)
const id = query.get('id')
let status = IS_ACTIVE;


account.addEventListener('change',checkAccount);
nickname.addEventListener('change',checkNickname);
email.addEventListener('change',checkEmail);
newPassword.addEventListener('change',checkNewPassword);
confirmPassword.addEventListener('change',checkConfirmPassword)

form.addEventListener("submit", async function (e) {
    e.preventDefault();
    checkAccount()
    checkNickname()
    checkEmail();
    if(!account.value||!nickname.value||!email.value)return;
    let radios = document.querySelectorAll('input[name="status"]');
    for (let i = 0; i < radios.length; i++) {
      if (radios[i].checked) {
        status = radios[i].value;
        break;
      }
    }
  try {
    const param = {
      id:id,  
      account: account.value,
      name: nickname.value,
      email:email.value,
      status: status,
      manage: "account",
      task: "edit",
    };
   loading.style.display = 'block';
      const response = await edit(param);
      loading.style.display = 'none';
      console.log(response);
       if (response.data.errCode === SUCCESS) {
            alertMessage.innerText = "成功";
            alertBtn.onclick = function () {
              location.href = "list.php";
            };
            alertLB.style.display = "block";
            console.log(response);
          } else {
            alertMessage.innerText = "系統錯誤";
            alertBtn.onclick = function () {
              alertLB.style.display = "none";
            };
            alertLB.style.display = "block";
          }
  } catch (error) {
    console.log(error)
  }
});

submitPassword.addEventListener('click',async function(e){
    e.preventDefault()
    checkNewPassword()
    checkConfirmPassword()
    if(!newPassword.value||!confirmPassword.value||newPassword.value!=confirmPassword.value)return;
    try {
        const param = {
          id:id,  
          newPassword:newPassword.value,
          confirmPassword:confirmPassword.value,  
          manage: "account",
          task: "edit-password",
        };
        passwordLB.style.display = 'none'
       loading.style.display = 'block';
          const response = await editPassword(param);
          loading.style.display = 'none';
          console.log(response);
           if (response.data.errCode === SUCCESS) {
                alertMessage.innerText = "成功";
                alertBtn.onclick = function () {
                  location.href = "list.php";
                };
                alertLB.style.display = "block";
                console.log(response);
              } else {
                alertMessage.innerText = "系統錯誤";
                alertBtn.onclick = function () {
                  alertLB.style.display = "none";
                };
                alertLB.style.display = "block";
              }
      } catch (error) {
        console.log(error)
      }
})

openPassword.addEventListener('click',function(e){
    e.preventDefault()
    passwordLB.style.display = 'block'
})

closePassword.addEventListener('click',function(e){
    e.preventDefault()
    passwordLB.style.display = 'none'
    newPassword.value = ''
    confirmPassword.value = ''
    newPasswordError.style.display = 'none'
    comfirmPasswordError.style.display = 'none'
})

cancel.addEventListener("click", function (e) {
    e.preventDefault();
    location.href = "list.php";
  });
  
  function checkAccount() {
    if (!account.value) {
      accountError.style.display = "inline";
      return;
    }
    accountError.style.display = "none";
  }
  
  
  
  
  function checkNickname(){
    if (!nickname.value) {
      nicknameError.style.display = "inline";
      return;
    }
    nicknameError.style.display = "none";
  }
  
  function checkEmail(){
    if (!email.value) {
      emailError.style.display = "inline";
      return;
    }
    emailError.style.display = "none";
  }

  function checkNewPassword(){
    if (!newPassword.value) {
      newPasswordError.style.display = "inline";
      return;
    }
    newPasswordError.style.display = "none";
  }

  function checkConfirmPassword(){
    if (!confirmPassword.value||newPassword.value!=confirmPassword.value) {
      comfirmPasswordError.style.display = "inline";
      return;
    }
    comfirmPasswordError.style.display = "none";
  }