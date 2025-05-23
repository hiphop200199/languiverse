import { REQUEST_BASE_URL } from "./admin/config.js";

export const create = async function (param) {
    const response = await  axios.post(REQUEST_BASE_URL+'/controller/admin/'+param['manage']+'.php',param, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });
    return response;
  };

export const edit = async function (param) {
  const response = await  axios.post(REQUEST_BASE_URL+'/controller/admin/'+param['manage']+'.php',param, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
  return response;
};

  export const destroy = async function (param) {
    const response = await  axios.post(REQUEST_BASE_URL+'/controller/admin/'+param['manage']+'.php',param, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
  return response;
  };

  export const exportFile = async function (param) {
    const response = await  axios.post(REQUEST_BASE_URL+'/controller/admin/'+param['manage']+'.php',param, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
  return response;
  };

 //web
 
 export const createRate = async function(param){
  const response = await axios.post(REQUEST_BASE_URL + '/controller/joke.php',param);
  return response;
 } 