import { useMutation } from "@tanstack/react-query";

import { axios } from "@/lib/axios";
import { MutationConfigType, queryClient } from "@/lib/react-query";

import { SampleFormValuesType, SampleType } from "../types";
import { useToast } from "@chakra-ui/react";

export type UpdateSampleType = {
    data: SampleFormValuesType;
    id: string;
     locale?: string;
};

export const updateSample = async ({
    data,
    id,
     locale,
}: UpdateSampleType): Promise<SampleType> => {
    const response = await axios.post(`samples/${id}`, data, {
        headers: { "X-Http-Method-Override": "PATCH" },
         params: locale ? { locale: locale } : {},
    });
    return response.data;
};

type useOptions = {
    config?: MutationConfigType<
        SampleType,
        UpdateSampleType,
        {
            previousData: SampleType | undefined;
        }
    >;
};

export const useUpdateSample = ({ config }: useOptions = {}) => {
    const toast = useToast();
    return useMutation(updateSample, {
        onMutate: async (updateData) => {
            // When mutate is called:
            await queryClient.cancelQueries(["samples"]);

            // Snapshot the previous value
            const previousData = queryClient.getQueryData<SampleType>([
                "samples",
                updateData.id,
            ]);

            // Optimistically update to the new value
            if (previousData) {
                queryClient.setQueryData(["samples", updateData.id], {
                    ...previousData,
                    ...updateData.data,
                    id: updateData.id,
                });
            }

            // Return a context object with the snapshotted value
            return { previousData };
        },
        // If the mutation fails, use the context returned from onMutate to roll back
        onError: (error, variables, context) => {
            if (context?.previousData) {
                queryClient.setQueryData(
                    ["samples", context.previousData.id],
                    context.previousData
                );
            }
            toast({
                position: "top",
                title: `更新に失敗しました`,
                status: "error",
                duration: 5000,
                isClosable: true,
            });
        },
        onSuccess(data, variables, context) {
            //
            toast({
                position: "top",
                title: `更新に成功しました`,
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
