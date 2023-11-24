import { getClientes } from "@/Api/clientes";


export const useClientes = () => {
    const searchByName = async (search) => {
        return await getClientes({
            search:search,
        });
    }

    return {
        searchByName,
    }
}
