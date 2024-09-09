import React, { lazy } from "react";
import { ContentLayout } from "@/components/Layout/ContentLayout";
import { FormProvider } from "@/providers/form";

const Create = lazy(() => import("@/features/samples/components/Create"));

function CreateIndex() {
    return (
        <FormProvider>
            <ContentLayout title="SAMPLE">
                <Create />
            </ContentLayout>
        </FormProvider>
    );
}

export default CreateIndex;
