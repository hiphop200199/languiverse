import { REQUEST_BASE_URL } from "../admin/config.js"
import { SUCCESS } from "../admin/constant.js"
import { frontend } from "../request_model.js";

const openCommentModal = document.getElementById('open-comment-modal')
const commentModal = document.getElementById('comment-modal')
const closeCommentModal = document.getElementById('cancel')
const form = document.getElementById('comment-add')
const loadingIcon = document.getElementById('loading-mask')
const alertLB = document.getElementById('alert-mask')
const alertIcon = document.getElementById('result')
const alertBtn = document.getElementById('alert-btn')
const query = new URLSearchParams(location.search)
const id = query.get('id')

openCommentModal.addEventListener('click',()=>{
    commentModal.showModal()
    document.documentElement.style.overflowY = 'hidden'
})
closeCommentModal.addEventListener('click',e=>{
    e.preventDefault()
    commentModal.close()
      document.documentElement.style.overflowY = 'auto'
})

form.addEventListener('submit',async function(e){
    e.preventDefault()
    try {
        const param = {
          id:id,  
          comment: document.querySelector('textarea[name="comment"]').value,
          score: document.querySelector('input[type="radio"]:checked').value,
          task: "create-rate",
        };
         commentModal.close()
        loadingIcon.style.display = 'block';
        const response = await frontend('joke',param);
        loadingIcon.style.display = 'none';
        console.log(response);
         if (response.data.errCode === SUCCESS) {
              alertBtn.onclick = function () {
                location.reload();
              };
              alertLB.style.display = "block";
              console.log(response);
            } else {
              alertIcon.src = REQUEST_BASE_URL + '/image/no-data.gif'
              alertBtn.onclick = function () {
                alertLB.style.display = "none";
              };
              alertLB.style.display = "block";
            }
      } catch (error) {
        console.log(error)
      }
})