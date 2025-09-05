import { SUCCESS } from "../constant.js";
import { edit } from "../../curse_model.js";
import { REQUEST_BASE_URL } from "../config.js";

const form = document.getElementById("curse-edit");
const content = document.getElementById("content");
const contentError = document.getElementById("content-error");
const cancel = document.getElementById("cancel");
const loading = document.getElementById("loading-mask");
const alertLB = document.getElementById("alert-mask");
const alertMessage = document.getElementById("alert-message");
const alertBtn = document.getElementById("submit-alert");
const confirmLB = document.getElementById("confirm-mask");
const query = new URLSearchParams(location.search);
const id = query.get("id");
let status;


content.addEventListener("change", checkContent);




form.addEventListener("submit", async function (e) {
  e.preventDefault();
  checkContent();
  
  if (!content.value) return;
  let radios = document.querySelectorAll('input[name="status"]');
  for (let i = 0; i < radios.length; i++) {
    if (radios[i].checked) {
      status = radios[i].value;
      break;
    }
  }

  try {
    const param = {
      id: id,
      content: content.value,
      status: status,
      manage: "curse",
      task: "edit",
    };
    loading.style.display = "block";
    const response = await edit(param);
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

