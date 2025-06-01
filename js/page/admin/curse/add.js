import { SUCCESS } from "../constant.js";
import { create } from "../../curse_model.js";
import { REQUEST_BASE_URL } from "../config.js";

const form = document.getElementById("curse-add");
const content = document.getElementById("content");
const contentError = document.getElementById("content-error");
const category = document.getElementById("category");
const categoryError = document.getElementById("category-error");
const imageFile = document.getElementById("image");
const imageSource = document.getElementById("upload-image-source");
const addStrategy = document.getElementById("add-strategy");
const strategyArea = document.getElementById("strategy-area");
const cancel = document.getElementById("cancel");
const loading = document.getElementById("loading-mask");
const alertLB = document.getElementById("alert-mask");
const alertMessage = document.getElementById("alert-message");
const alertBtn = document.getElementById("submit-alert");
const confirmLB = document.getElementById("confirm-mask");
let status;
let tag = [];

content.addEventListener("change", checkContent);
category.addEventListener("change", checkCategory);

imageFile.addEventListener("change", function (e) {
  const file = imageFile.files[0];
  const allowFileTypes = ["image/png", "image/jpg", "image/jpeg", "image/gif"];
  if (file.size > 1 * 1024 * 1024) {
    imageFile.value = "";
    alertMessage.innerText = "檔案太大囉！";
    alertBtn.onclick = function () {
      alertLB.style.display = "none";
    };
    alertLB.style.display = "block";
    return;
  }

  if (!allowFileTypes.includes(file.type)) {
    imageFile.value = "";
    alertMessage.innerText = "檔案格式不符";
    alertBtn.onclick = function () {
      alertLB.style.display = "none";
    };
    alertLB.style.display = "block";
    return;
  }

  const reader = new FileReader();
  reader.onload = function (e) {
    imageSource.src = e.target.result;
  };
  reader.readAsDataURL(file);
});

addStrategy.addEventListener("click", function (e) {
  e.preventDefault();
  let strategy = document.createElement("div");
  let close = document.createElement("button");
  let num = document.createElement("p");
  let label1 = document.createElement("label");
  let content = document.createElement("textarea");
  let strategyIndex = strategyArea.childElementCount + 1;

  strategy.dataset.id = strategyIndex;
  strategy.classList.add("strategy");
  close.dataset.id = strategyIndex;
  close.classList.add("delete-strategy");
  close.innerText = "✖";
  close.onclick = function () {
    this.parentElement.remove();
  };
  num.dataset.id = strategyIndex;
  num.innerText = "編號：" + strategyIndex;
  label1.innerText = "內容";
  content.classList.add("content");
  strategy.append(close, num, label1, content);
  strategyArea.appendChild(strategy);
});

form.addEventListener("submit", async function (e) {
  e.preventDefault();
  checkContent();
  checkCategory();
  if (!content.value || !category.value) return;
  let radios = document.querySelectorAll('input[name="status"]');
  for (let i = 0; i < radios.length; i++) {
    if (radios[i].checked) {
      status = radios[i].value;
      break;
    }
  }
  let tags = document.querySelectorAll('input[name="tag"]');
  tag = [];
  for (let i = 0; i < tags.length; i++) {
    if (tags[i].checked) {
      tag.push(tags[i].value);
    }
  }
  tag.sort((a, b) => a - b);
  let strategies = [];
  let strategiesNodes = document.querySelectorAll(".strategy");
  for (let i = 0; i < strategiesNodes.length; i++) {
    let strategy = {};
    strategy.num = strategiesNodes[i].dataset.id;
    strategy.content = strategiesNodes[i].children[3].value;
    strategies.push(strategy);
  }
  try {
    const param = {
      content: content.value,
      category: category.value,
      tag: tag.join(),
      strategy: JSON.stringify(strategies),
      status: status,
      image: imageFile.files[0],
      manage: "curse",
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

function checkCategory() {
  if (!category.value) {
    categoryError.style.display = "inline";
    return;
  }
  categoryError.style.display = "none";
}
