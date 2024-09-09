import { useMutation } from "@tanstack/react-query";

import { axios } from "@/lib/axios";
import { MutationConfigType } from "@/lib/react-query";

import { SampleFormValuesType } from "../types";
import { ConfirmResponseType } from "@/types";
import { useToast } from "@chakra-ui/react";

export type ConfirmSampleType = {
    data: SampleFormValuesType;
    id?: string
    locale?: string;
};

export const confirmSample = async ({
    data,
    id = undefined,
     locale,
}: ConfirmSampleType): Promise<ConfirmResponseType> => {
    const response = id
        ? await axios.post(`samples/confirm/${id}`, data  ,{
              params: locale ? { locale: locale } : {},
          })
        : await axios.post(`samples/confirm`, data);
    return response.data;
};

type useOptions = {
    config?: MutationConfigType<
        ConfirmResponseType,
        ConfirmSampleType,
        {
            previousData: undefined;
        }
    >;
};

export const useConfirmSample = ({ config }: useOptions = {}) => {
    const toast = useToast();
    return useMutation(confirmSample, {
        onError: (error, variables, context) => {
            toast({
                position: "top",
                title: `確認に失敗しました`,
                status: "error",
                duration: 5000,
                isClosable: true,
            });
        },
        ...config,
    });
};
