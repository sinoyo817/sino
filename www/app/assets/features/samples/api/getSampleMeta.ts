import { useQuery } from "@tanstack/react-query";

import { axios } from "@/lib/axios";

import { QueryConfigType } from "@/lib/react-query";
import { SampleMetaType } from "../types";

const getSampleMeta = async (): Promise<SampleMetaType> => {
    const response = await axios.get(`samples/metadata`);
    return response.data;
};

type useOptions = {
    options?: QueryConfigType<SampleMetaType>;
};

export const useSampleMeta = ({ options }: useOptions = {}) => {
    return useQuery(
        ["samples-meta"],
        () => getSampleMeta(),
        options
    );
};

