import { REQUEST_BASE_URL } from "./admin/config.js";

export const backend = async function (param) {
  const response = await  axios.post(REQUEST_BASE_URL+'/controller/admin/'+param['manage']+'.php',param, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });
  return response;
};

//web

 export const frontend = async function(manage,param){
  const response = await axios.post(REQUEST_BASE_URL + '/controller/'+manage+'.php',param, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });
  return response;
 }


