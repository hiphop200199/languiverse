import { REQUEST_BASE_URL } from "../admin/config.js";
import { SUCCESS } from "../admin/constant.js";
import { frontend } from "../request_model.js";

const loadingIcon = document.getElementById("loading-mask");
const alertLB = document.getElementById("alert-mask");
const alertIcon = document.getElementById("result");
const alertBtn = document.getElementById("alert-btn");
const testExplainBtn = document.getElementById("test-explain-btn");
const closeTestExplainBtn = document.getElementById("close-test-explain");
const testExplainModal = document.getElementById("test-explain-modal");
const startTestBtn = document.getElementById("start-test");
const nextQuestionBtn = document.getElementById("next-question-btn");
const cancelTestBtn = document.getElementById("cancel-test-btn");
const shareFbBtn = document.getElementById("share-fb");
const shareLineBtn = document.getElementById("share-line");
const testBoxBtns = document.querySelectorAll(".test-box-btn");
const duringTest = document.querySelectorAll(".during-test");
const testingBox = document.getElementById("testing-box");
const testCoverImg = document.getElementById("test-cover-img");
const scoreModal = document.getElementById("score-modal");
const closeScoreBtn = document.getElementById("close-score");
const exportPdfBtn = document.getElementById('export-pdf')
const scoreModalTitle = document.getElementById("score-modal-title");
let questionNumber = 1;
let score = 0;
let testQuestion = document.getElementById("test-question");
let testQuestionAudio = document.getElementById('test-question-audio')
let answerArea = document.getElementById("answers");

testExplainBtn.addEventListener("click", function () {
  testExplainModal.showModal();
});
closeTestExplainBtn.addEventListener("click", function () {
  testExplainModal.close();
});
startTestBtn.addEventListener("click", async function () {
  try {
    const param = {
      task: "get-random-question",
    };
    loadingIcon.style.display = "block";
    const response = await frontend('joke',param);
    loadingIcon.style.display = "none";
    console.log(response);
    if (response.data.errCode === SUCCESS) {
      const answerArray = [];
      const theRightNum = Math.floor(Math.random() * 4);
      for (let i = 0; i < 4; i++) {
        let label = document.createElement("label");
        let radio = document.createElement("input");
        let labelAnswer = document.createTextNode(response.data.list[i].answer);
        radio.type = "radio";
        radio.value = 0;
        radio.name = "answer";
        if (i == theRightNum) {
          testQuestion.innerText = response.data.list[i].question;
          radio.value = 5;
        }
        label.appendChild(radio);
        label.appendChild(labelAnswer);
        answerArray.push(label);
      }
      for (let i = 0; i < 4; i++) {
        const num = Math.floor(Math.random() * answerArray.length);
        answerArea.appendChild(answerArray[num]);
        answerArray.splice(num, 1);
      }
    } else {
      alertIcon.src = REQUEST_BASE_URL + "/image/no-data.gif";
      alertBtn.onclick = function () {
        alertLB.style.display = "none";
      };
      alertLB.style.display = "block";
    }
  } catch (error) {
    console.log(error);
  }
  testBoxBtns.forEach((t) => (t.style.display = "none"));
  duringTest.forEach((t) => (t.style.display = "inline"));
  testingBox.style.display = "flex";
  testCoverImg.style.display = "none";
});
nextQuestionBtn.addEventListener("click", async function () {
  questionNumber+=1
  if (questionNumber > 20) {
    testQuestion.style.display = 'block'
    testQuestionAudio.style.display = 'none'
    duringTest.forEach((t) => (t.style.display = "none"));
    testBoxBtns.forEach((t) => (t.style.display = "inline"));
    testingBox.style.display = "none";
    testCoverImg.style.display = "block";
    scoreModalTitle.innerText = "恭喜您得到" + score + "分！";
    scoreModal.showModal();
  } else {
    if(questionNumber == 20){
      nextQuestionBtn.innerText = '結束';
    }
    let checked = document.querySelector('input[name="answer"]:checked')
    if(checked){
      score += parseInt(checked.value,10)
    }
    while(answerArea.firstChild){
      answerArea.removeChild(answerArea.firstChild)
    }
    try {
      const param = {
        task: "get-random-question",
      };
      loadingIcon.style.display = "block";
      const response = await jokeRequest(param);
      loadingIcon.style.display = "none";
      console.log(response);
      if (response.data.errCode === SUCCESS) {
        const answerArray = [];
        const theRightNum = Math.floor(Math.random() * 4);
        for (let i = 0; i < 4; i++) {
          let label = document.createElement("label");
          let radio = document.createElement("input");
          let labelAnswer = document.createTextNode(
            response.data.list[i].answer
          );
          radio.type = "radio";
          radio.value = 0;
          radio.name = "answer";
          if (i == theRightNum) {
            if(questionNumber < 15){
              testQuestion.innerText = response.data.list[i].question;
            }else{
              testQuestion.style.display = 'none'
             testQuestionAudio.style.display = 'block' 
              testQuestionAudio.src = response.data.list[i].mp3
            }
            radio.value = 5;
          }
          label.appendChild(radio);
          label.appendChild(labelAnswer);
          answerArray.push(label);
        }
        for (let i = 0; i < 4; i++) {
          const num = Math.floor(Math.random() * answerArray.length);
          answerArea.appendChild(answerArray[num]);
          answerArray.splice(num, 1);
        }
      } else {
        alertIcon.src = REQUEST_BASE_URL + "/image/no-data.gif";
        alertBtn.onclick = function () {
          alertLB.style.display = "none";
        };
        alertLB.style.display = "block";
      }
    } catch (error) {
      console.log(error);
    }
  }
});
cancelTestBtn.addEventListener("click", function () {
  duringTest.forEach((t) => (t.style.display = "none"));
  testBoxBtns.forEach((t) => (t.style.display = "inline"));
  testingBox.style.display = "none";
  testCoverImg.style.display = "block";
  questionNumber = 1;
  score = 0;
});

shareFbBtn.addEventListener("click", function () {
  window.open(
    "https://www.facebook.com/sharer/sharer.php?u=" +
      encodeURIComponent(location.href)
  );
});

shareLineBtn.addEventListener("click", function () {
  window.open(
    "https://social-plugins.line.me/lineit/share?url=" +
      encodeURIComponent(location.href)
  );
});

closeScoreBtn.addEventListener("click", function () {
  scoreModal.close();
  nextQuestionBtn.innerText = '下一題'
  questionNumber = 1;
  score = 0;
});

exportPdfBtn.addEventListener('click',async function(){
    location.href = REQUEST_BASE_URL + '/controller/joke.php?mode=export&score='+score
})