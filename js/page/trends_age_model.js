
import { REQUEST_BASE_URL } from "./admin/config.js";

export const create = async function (param) {
  const response = await  axios.post(REQUEST_BASE_URL+'/controller/admin/'+param['manage']+'.php',param);
  return response;
};

export const edit =async function (param) {
  const response = await  axios.post(REQUEST_BASE_URL+'/controller/admin/'+param['manage']+'.php',param);
  return response;
};

export const destroy = async function (param) {
  const response = await  axios.post(REQUEST_BASE_URL+'/controller/admin/'+param['manage']+'.php',param);
  return response;
};

export const editPassword = function (param) {};

export const forgotPassword = function (param) {};

export const exportFile = async function (param) {
  const response = await  axios.post(REQUEST_BASE_URL+'/controller/admin/'+param['manage']+'.php',param);
  return response;
};
