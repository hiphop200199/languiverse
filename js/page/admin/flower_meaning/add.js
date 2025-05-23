import { SUCCESS } from "../constant.js";
import { create } from "../../flower_meaning_model.js";

const form = document.getElementById("flower-meaning-add");
const content = document.getElementById("content");
const contentError = document.getElementById("content-error");
const story = document.getElementById('story');
const category = document.getElementById("category");
const categoryError = document.getElementById("category-error");
const imageFile = document.getElementById('image');
const imageSource = document.getElementById('upload-image-source')
const cancel = document.getElementById('cancel');
const loading = document.getElementById("loading-mask");
const alertLB = document.getElementById("alert-mask");
const alertMessage = document.getElementById('alert-message')
const alertBtn = document.getElementById('submit-alert')
const confirmLB = document.getElementById("confirm-mask");
let status;

content.addEventListener("change", checkContent);
category.addEventListener("change", checkCategory);

imageFile.addEventListener('change',function(e){
  const file = imageFile.files[0]
  const allowFileTypes = ['image/png','image/jpg','image/jpeg','image/gif']
  if(file.size > 1 * 1024 * 1024){
    imageFile.value = ''
    alertMessage.innerText = '檔案太大囉！'
    alertBtn.onclick = function(){
      alertLB.style.display = 'none'
    }
    alertLB.style.display = 'block'
    return;
  }

  if(!allowFileTypes.includes(file.type)){
      imageFile.value = ''
    alertMessage.innerText = '檔案格式不符'
    alertBtn.onclick = function(){
      alertLB.style.display = 'none'
    }
    alertLB.style.display = 'block'
    return;
  }

  const reader = new FileReader()
  reader.onload = function(e){
    imageSource.src = e.target.result;
  }
  reader.readAsDataURL(file);

})

form.addEventListener("submit", async function (e) {
  e.preventDefault();
  checkContent();
  checkCategory();
  if(!content.value||!category.value)return;
  let radios = document.querySelectorAll('input[name="status"]');
  for (let i = 0; i < radios.length; i++) {
    if (radios[i].checked) {
      status = radios[i].value;
      break;
    }
  }

  try {
    const param = {
      content: content.value,
      story:story.value,
      category:category.value,
      status: status,
      image:imageFile.files[0],
      manage: "flower_meaning",
      task: "create",
    };
    loading.style.display = "block";
    const response = await create(param);
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

cancel.addEventListener("click", function (e) {
  e.preventDefault();
  location.href = "list.php";
});

function checkContent() {
  if (!content.value) {
    contentError.style.display = "inline";
    return;
  }
  contentError.style.display = "none";
}


function checkCategory(){
  if (!category.value) {
    categoryError.style.display = "inline";
    return;
  }
  categoryError.style.display = "none";
}