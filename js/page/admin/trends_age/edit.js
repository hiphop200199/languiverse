import { SUCCESS } from "../constant.js";
import { backend } from "../../request_model.js";

const form = document.getElementById("trends-age-edit");
const name = document.getElementById("name");
const nameError = document.getElementById("name-error");
const cancel = document.getElementById('cancel');

const loading = document.getElementById("loading-mask");
const alertLB = document.getElementById("alert-mask");
const alertMessage = document.getElementById('alert-message')
const alertBtn = document.getElementById('submit-alert')
const confirmLB = document.getElementById("confirm-mask");
const query = new URLSearchParams(location.search)
const id = query.get('id')
let status;


name.addEventListener("change", check);

form.addEventListener("submit", async function (e) {
  e.preventDefault();
  check();
  if (!name.value) return;
  let radios = document.querySelectorAll('input[name="status"]');
  for (let i = 0; i < radios.length; i++) {
    if (radios[i].checked) {
      status = radios[i].value;
      break;
    }
  }
  try {
    const param = {
      id:  id,
      name: name.value,
      status: status,
      manage: "trends_age",
      task: "edit",
    };
    loading.style.display = "block";
    const response = await backend(param);
    loading.style.display = "none";
    console.log(response);

     if(response.data.errCode ===SUCCESS){
      alertMessage.innerText = '成功'
      alertBtn.onclick = function(){
        const pathName = location.pathname.split("/");
        pathName[pathName.length - 1] = response.data.redirect;
        const newPathName = pathName.join("/");
        location.pathname = newPathName;
      }
      alertLB.style.display = 'block';
      console.log(response);     
    } else{
      alertMessage.innerText = '系統錯誤'
      alertBtn.onclick = function(){
        alertLB.style.display = 'none'
      }
      alertLB.style.display = 'block'
    }
  } catch (error) {
    console.log(error);
  }
});



cancel.addEventListener('click',function(e){
  e.preventDefault();
  location.href = 'list.php';
})

function check(){
  if (name.value) {
    nameError.style.display = "none";
  } else {
    nameError.style.display = "inline";
  }
}