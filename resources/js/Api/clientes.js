import axios from 'axios';

export const getClientes = async (params = {}) => {
    const response = await axios.post(route('admin.clientes.select2'),params);

    if(response.status == 200){
      return response.data.results;
    }

    throw response;
}
