import { useQuery } from "@tanstack/react-query";

import { axios } from "@/lib/axios";

import { SampleFilterParamType, SampleListType } from "../types";
import { QueryConfigType } from "@/lib/react-query";

type getOptions = {
    filters?: SampleFilterParamType;
};

const getSamples = async ({ filters }: getOptions): Promise<SampleListType> => {
    const response = await axios.get("samples", { params: filters });
    return response.data;
};

type useOptions = {
    options?: QueryConfigType<SampleListType>;
    filters?: SampleFilterParamType;
};

export const useSamples = (props: useOptions) => {
    const { filters = {}, options = {} } = props;
    return useQuery(
        [
            "samples",
            Object.keys(filters).length > 0 ? JSON.stringify(filters) : "all",
        ],
        () => getSamples({ filters: { ...filters } }),
        options
    );
};
