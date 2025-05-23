import { REQUEST_BASE_URL } from "./admin/config.js";

export const create = async function (param) {
  const response = await  axios.post(REQUEST_BASE_URL+'/controller/admin/'+param['manage']+'.php',param);
  return response;
};

export const edit = async function (param) {
  const response = await  axios.post(REQUEST_BASE_URL+'/controller/admin/'+param['manage']+'.php',param);
  return response;
};

export const destroy = async function (param) {
  const response = await  axios.post(REQUEST_BASE_URL+'/controller/admin/'+param['manage']+'.php',param);
  return response;
};



export const editPassword = async function (param) {
  const response = await  axios.post(REQUEST_BASE_URL+'/controller/admin/'+param['manage']+'.php',param);
  return response;
};

export const login = async function (param) {
  const response = await  axios.post(REQUEST_BASE_URL+'/controller/admin/'+param['manage']+'.php',param);
  return response;
};

export const logout = async function (param) {
  const response = await  axios.post(REQUEST_BASE_URL+'/controller/admin/'+param['manage']+'.php',param);
  return response;
};

export const forgotPassword = async function (param) {
  const response = await  axios.post(REQUEST_BASE_URL+'/controller/admin/'+param['manage']+'.php',param);
  return response;
};

export const validateResetCode = async function (param) {
  const response = await  axios.post(REQUEST_BASE_URL+'/controller/admin/'+param['manage']+'.php',param);
  return response;
};

export const resetPassword = async function (param) {
  const response = await  axios.post(REQUEST_BASE_URL+'/controller/admin/'+param['manage']+'.php',param);
  return response;
};

export const exportFile = function (param) {};
