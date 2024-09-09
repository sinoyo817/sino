import { useMutation } from "@tanstack/react-query";

import { axios } from "@/lib/axios";
import { MutationConfigType, queryClient } from "@/lib/react-query";

import { SampleFormValuesType, SampleListType, SampleType } from "../types";
import { useToast } from "@chakra-ui/react";

export type CreateSampleType = { data: SampleFormValuesType };

export const createSample = async ({
    data,
}: CreateSampleType): Promise<SampleType> => {
    const response = await axios.post(`samples`, data);
    return response.data;
};

type useOptions = {
    config?: MutationConfigType<
        SampleType,
        CreateSampleType,
        {
            previousData: SampleListType | undefined;
        }
    >;
};

export const useCreateSample = ({ config }: useOptions = {}) => {
    const toast = useToast();
    return useMutation(createSample, {
        onMutate: async (newData) => {
            // When mutate is called:
            await queryClient.cancelQueries(["samples"]);

            // Snapshot the previous value
            const previousData = queryClient.getQueryData<SampleListType>([
                "samples",
            ]);

            // Optimistically update to the new value
            if (previousData) {
                queryClient.setQueryData(["samples"], {
                    data: [...(previousData.data || []), newData.data],
                    collection: previousData.collection,
                });
            }

            // Return a context object with the snapshotted value
            return { previousData };
        },
        // If the mutation fails, use the context returned from onMutate to roll back
        onError: (error, variables, context) => {
            if (context?.previousData) {
                queryClient.setQueryData(["samples"], context.previousData);
            }
            toast({
                position: "top",
                title: `登録に失敗しました`,
                status: "error",
                duration: 5000,
                isClosable: true,
            });
        },
        onSuccess(data, variables, context) {
            toast({
                position: "top",
                title: `登録に成功しました`,
                status: "success",
                duration: 5000,
                isClosable: true,
            });
        },
        // Always refetch after error or success:
        onSettled: () => {
            queryClient.invalidateQueries(["samples"]);
        },
        ...config,
    });
};
