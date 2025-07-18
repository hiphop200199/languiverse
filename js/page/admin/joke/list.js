import { SUCCESS } from "../constant.js";
import { destroy, exportFile } from "../../joke_model.js";
import { REQUEST_BASE_URL } from "../config.js";


const deleteBtn = document.querySelectorAll(".delete");
const loading = document.getElementById("loading-mask");
const alertLB = document.getElementById("alert-mask");
const alertMessage = document.getElementById("alert-message");
const alertBtn = document.getElementById("submit-alert");
const confirmLB = document.getElementById("confirm-mask");
const confirmLBMessage = document.getElementById("confirm-message");
const confirmLBBtn = document.getElementById("submit-confirm");
const confirmLBCancel = document.getElementById("close-confirm");
const exportBtn = document.getElementById('export')
const format = document.getElementById('format')
const searchBtn = document.getElementById('search')
const category = document.getElementById('category')
let id;

confirmLBMessage.innerText = "確認刪除？";
confirmLBCancel.addEventListener("click", function () {
  confirmLB.style.display = "none";
});
confirmLBBtn.addEventListener("click", async function () {
  try {
    const param = {
      id: id,
      manage: "joke",
      task: "delete",
    };
    confirmLB.style.display = "none";
    loading.style.display = "block";
    const response = await destroy(param);
    loading.style.display = "none";
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
    console.log(error);
  }
});
for (let i = 0; i < deleteBtn.length; i++) {
  deleteBtn[i].addEventListener("click", function () {
    confirmLB.style.display = "block";
    id = deleteBtn[i].dataset.id;
  });
}


exportBtn.addEventListener('click',async function(){
  if(!format.value) return;
    location.href = REQUEST_BASE_URL + '/controller/admin/joke.php?mode=export&format='+format.value
})

searchBtn.addEventListener('click',function(){
  if(!category.value) return;
  location.href = REQUEST_BASE_URL + '/page/admin/joke/list.php?category='+category.value
})