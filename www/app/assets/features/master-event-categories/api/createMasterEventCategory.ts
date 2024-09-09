import { useMutation } from "@tanstack/react-query";

import { axios } from "@/lib/axios";
import { MutationConfigType, queryClient } from "@/lib/react-query";

import { MasterEventCategoryFormValuesType, MasterEventCategoryListType, MasterEventCategoryType } from "../types";
import { useToast } from "@chakra-ui/react";

export type CreateMasterEventCategoryType = { data: MasterEventCategoryFormValuesType };

export const createMasterEventCategory = async ({
    data,
}: CreateMasterEventCategoryType): Promise<MasterEventCategoryType> => {
    const response = await axios.post(`master-event-categories`, data);
    return response.data;
};

type useOptions = {
    config?: MutationConfigType<
        MasterEventCategoryType,
        CreateMasterEventCategoryType,
        {
            previousData: MasterEventCategoryListType | undefined;
        }
    >;
};

export const useCreateMasterEventCategory = ({ config }: useOptions = {}) => {
    const toast = useToast();
    return useMutation(createMasterEventCategory, {
        onMutate: async (newData) => {
            // When mutate is called:
            await queryClient.cancelQueries(["master-event-categories"]);

            // Snapshot the previous value
            const previousData = queryClient.getQueryData<MasterEventCategoryListType>([
                "master-event-categories",
            ]);

            // Optimistically update to the new value
            if (previousData) {
                queryClient.setQueryData(["master-event-categories"], {
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
                queryClient.setQueryData(["master-event-categories"], context.previousData);
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
            queryClient.invalidateQueries(["master-event-categories"]);
        },
        ...config,
    });
};
