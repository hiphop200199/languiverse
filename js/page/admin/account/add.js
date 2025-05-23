import { IS_ACTIVE, IS_ADMIN, IS_NOT_ADMIN, SUCCESS } from "../constant.js";
import { create } from "../../account_model.js";

const form = document.getElementById("account-add");
const account = document.getElementById("account");
const accountError = document.getElementById('account-error')
const password = document.getElementById("password");
const passwordError = document.getElementById('password-error')
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
let status = IS_ACTIVE;
let is_admin = IS_NOT_ADMIN;

account.addEventListener('change',checkAccount);
password.addEventListener('change',checkPassword);
nickname.addEventListener('change',checkNickname);
email.addEventListener('change',checkEmail);

form.addEventListener("submit", async function (e) {
  e.preventDefault();
  checkAccount();
  checkPassword()
  checkNickname()
  checkEmail();
  if(!account.value||!password.value||!nickname.value||!email.value)return;
  let radios = document.querySelectorAll('input[name="status"]');
  for (let i = 0; i < radios.length; i++) {
    if (radios[i].checked) {
      status = radios[i].value;
      break;
    }
  }
  try {
    const param = {
      account: account.value,
      password:password.value,
      name: nickname.value,
      email:email.value,
      status: status,
      is_admin: is_admin,
      manage: "account",
      task: "create",
    };
    loading.style.display = 'block';
    const response = await create(param);
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

function checkPassword() {
  if (!password.value) {
    passwordError.style.display = "inline";
    return;
  }
  passwordError.style.display = "none";
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


