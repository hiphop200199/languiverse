import { SUCCESS } from "../constant.js";
import { create } from "../../joke_model.js";

const form = document.getElementById("joke-add");
const question = document.getElementById("question");
const questionError = document.getElementById("question-error");
const answer = document.getElementById("answer");
const answerError = document.getElementById("answer-error");
const inspiration = document.getElementById('inspiration');
const category = document.getElementById("category");
const categoryError = document.getElementById("category-error");
const imageFile = document.getElementById('image');
const imageSource = document.getElementById('upload-image-source')
const mp3 = document.getElementById('mp3')
const cancel = document.getElementById("cancel");
const loading = document.getElementById("loading-mask");
const alertLB = document.getElementById("alert-mask");
const alertMessage = document.getElementById("alert-message");
const alertBtn = document.getElementById("submit-alert");
const confirmLB = document.getElementById("confirm-mask");
let status;
let tag = []; 

question.addEventListener("change", checkQuestion);
answer.addEventListener("change",checkAnswer);
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
  checkQuestion();
  checkAnswer();
  checkCategory();
  if(!question.value||!answer.value||!category.value)return;
  let radios = document.querySelectorAll('input[name="status"]');
  for (let i = 0; i < radios.length; i++) {
    if (radios[i].checked) {
      status = radios[i].value;
      break;
    }
  }
  let tags = document.querySelectorAll('input[name="tag"]');
  tag = [];
  for(let i=0;i<tags.length;i++){
    if(tags[i].checked){
      tag.push(tags[i].value);
    }
  }
  tag.sort((a,b)=> a - b);
  try {
    const param = {
      question: question.value,
      answer:answer.value,
      inspiration:inspiration.value,
      category:category.value,
      tag:tag.join(),
      status: status,
      mp3:mp3.files[0],
      image:imageFile.files[0],
      manage: "joke",
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

function checkQuestion() {
  if (!question.value) {
    questionError.style.display = "inline";
    return;
  }
  questionError.style.display = "none";
}

function checkAnswer(){
  if (!answer.value) {
    answerError.style.display = "inline";
    return;
  }
  answerError.style.display = "none";
}

function checkCategory(){
  if (!category.value) {
    categoryError.style.display = "inline";
    return;
  }
  categoryError.style.display = "none";
}