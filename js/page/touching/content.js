import { REQUEST_BASE_URL } from "../admin/config.js"
import { SUCCESS } from "../admin/constant.js"
import { createThought } from "../touching_model.js"

const openThoughtModal = document.getElementById('open-thought-modal')
const thoughtModal = document.getElementById('thought-modal')
const closeThoughtModal = document.getElementById('cancel')
const form = document.getElementById('thought-add')
const loadingIcon = document.getElementById('loading-mask')
const alertLB = document.getElementById('alert-mask')
const alertIcon = document.getElementById('result')
const alertBtn = document.getElementById('alert-btn')
const query = new URLSearchParams(location.search)
const id = query.get('id')

openThoughtModal.addEventListener('click',()=>{
    thoughtModal.showModal()
    thoughtModal.classList.add('open')
    document.documentElement.style.overflowY = 'hidden'
})
closeThoughtModal.addEventListener('click',e=>{
    e.preventDefault()
    thoughtModal.classList.remove('open')
    setTimeout(() => {
         thoughtModal.close()
      document.documentElement.style.overflowY = 'auto'
    }, 1000);
 
})

form.addEventListener('submit',async function(e){
    e.preventDefault()
    try {
        const param = {
          id:id,  
          thought: document.querySelector('textarea[name="thought"]').value,
          task: "create-thought",
        };
         thoughtModal.close()
        loadingIcon.style.display = 'block';
        const response = await createThought(param);
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