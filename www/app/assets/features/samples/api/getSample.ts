import { useQuery } from "@tanstack/react-query";

import { axios } from "@/lib/axios";

import { SampleType } from "../types";
import { QueryConfigType } from "@/lib/react-query";

const getSample = async ({
    id
    ,locale }: {
        id: string;
        locale?: string; 
    }): Promise<SampleType> => {
    const response = await axios.get(`samples/${id}` , {
        params: locale ? { locale: locale } : {},
    });
    return response.data;
};

type useOptions = {
    id: string;
     locale?: string;
    options?: QueryConfigType<SampleType>;
};

export const useSample = ({ id, options , locale }: useOptions) => {
     
        const cacheKey = locale ? `${id}-${locale}` : id;
        return useQuery(
            ["samples", cacheKey],
            () => getSample({ id, locale }),
            options
        );
     

};
